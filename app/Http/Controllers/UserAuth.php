<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUser;
use App\Jobs\SendOtpGeneratedEmail;
use App\Models\OTP_LOG;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserAuth extends Controller
{
    public function __construct()
    {
        
    }

    public function load_view()
    {
        /**
         * ------------------------
         *    load the view of the login page
         * ----------------------
         * */ 
    }

    public function login_perform(LoginUser $request)
    {
        $credentials=$request->credentials();
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect(RouteServiceProvider::HOME);
        }else {
            return back()->withErrors([
                'email'=>"invalid Credentials"
            ])->onlyInput('email');
        }
    }

    public function logout_perform(Request $request)
    {
        Auth::logout();
        return redirect('user/login');
    }

    public function send_otp(Request $request)
    {
        try {
            $validator=Validator::make($request->all(),[
                'email'=>"required|email"
            ],[
                'email.required'=>"provide an email id",
                "email.email"=>"provided email id must be a valid email"
            ]);
            if ($validator->fails()) {
                $body=[
                    'message'=>$validator->errors(),
                ];
                $response=CreateResponse([],$body,500);
            } else {
                DB::beginTransaction();
                $user=User::where('email',$request->input('email'))->first();
                if ($user!=null) {
                    if ($user?->exhuasted_otp_limit === $user?->otp_limit) {
                        $body=[
                            'message'=>"Your OTP Limit is Exhuasted Try After Sometime",
                        ];
                        $response=CreateResponse([],$body,500);
                    }
                    $code=rand(100000,999999);
                    $otp_log=new OTP_LOG();
                    $otp_log->mode="email";
                    $otp_log->code=$code;
                    $otp_log->user_id=$user?->id;
                    $otp_log->expires_at=Carbon::now()->addMinutes(1);
                    if ($otp_log->save()) {
                        $update_limit_exhuasted=User::where('id',$user?->id)->update([
                            'exhuasted_otp_limit'=>$user?->exhuasted_otp_limit+1
                        ]);
                        DB::commit();
                        $body=[
                            'message'=>"Otp Sent Successfully",
                        ];
                        $response=CreateResponse([],$body,200);
                        SendOtpGeneratedEmail::dispatch($code,env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'),$request->input('email'));
                    }else{
                        DB::rollBack();
                        $body=[
                            'status_text'=>"Operation Failed : save otp to log",
                        ];
                        $response=CreateResponse([],$body,500);
                    }
                }else {
                    DB::rollBack();
                        $body=[
                            'status_text'=>"Operation Failed : no user found with Email id {$request->input('email')}",
                        ];
                        $response=CreateResponse([],$body,500);
                }

            }
            
        } catch (Exception $e) {
            $headers=[];
            $body=['status_text'=>"Error Occured : ".$e->getMessage()." on Line ".$e->getLine()." File ".$e->getFile()];
            $response=CreateResponse($headers,$body,500);
            DB::rollBack();
        }
        return $response;
    }

    public function verify_otp(Request $request)
    {
        try {
            $validator=Validator::make($request->all(),[
                'email'=>"required|email",
                'otp'=>"required|digits:6"
            ],[
                'email.required'=>"provide an email id",
                "email.email"=>"provided email id must be a valid email",
                'otp.required'=>"otp required",
                'otp.digits'=>"OTP must me a 6-Digit Code",
            ]);
            if ($validator->fails()) {
                $body=[
                    'message'=>$validator->errors(),
                ];
                $response=CreateResponse([],$body,500);
            } else {
                DB::beginTransaction();
                $user=User::where('email',$request->input('email'))->first();
                if ($user==null) {
                    DB::rollBack();
                    $body=[
                        'status_text'=>"Operation Failed : no user found with Email id {$request->input('email')}",
                    ];
                    $response=CreateResponse([],$body,500);
                }else {
                    $latest_otp=OTP_LOG::where('user_id',$user?->id)->where('code',$request->input('otp'))->whereNull('expired_at')->orderBy('id','DESC')->first();
                    if ($latest_otp==null) {
                        $body=[
                            'status_text'=>"Operation Failed : No OTP Records Found",
                            'message'=>"Invalid OTP",
                        ];
                        $response=CreateResponse([],$body,500);
                    }
                    else {
                        $current_time=Carbon::now()->timestamp;
                        $otp_expires_at=strtotime($latest_otp?->expires_at);
                        if ($current_time > $otp_expires_at) {
                            OTP_LOG::where('id',$latest_otp?->id)->update([
                                'expired_at'=>Carbon::now()
                            ]);
                            $body=[
                                'status_text'=>"Your One Time Password has Expired",
                                'message'=>"OTP Expired",
                            ];
                            $response=CreateResponse([],$body,500);
                            DB::commit();
                        }else{
                            if ($request->input('otp') == $latest_otp?->code) {
                                
                                User::where('id',$user?->id)->update([
                                    'email_verified_at'=>Carbon::now(),
                                ]);
    
                                OTP_LOG::where('id',$latest_otp?->id)->update([
                                    'expired_at'=>Carbon::now()
                                ]);
                                DB::commit();
                                $body=[
                                    'message'=>"Email Verified",
                                ];
                                $response=CreateResponse([],$body,302);
                            }else{
                                $body=[
                                    'status_text'=>"You Have Entered Wrong OTP",
                                ];
                                $response=CreateResponse([],$body,500);
                            }

                        }
                    }
                }
                
            }
            
        } catch (Exception $e) {
            $headers=[];
            $body=['status_text'=>"Error Occured : ".$e->getMessage()." on Line ".$e->getLine()." File ".$e->getFile()];
            $response=CreateResponse($headers,$body,500);
            DB::rollBack();
        }
        return $response;
    }
    
}
