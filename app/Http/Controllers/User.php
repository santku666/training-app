<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewUser;
use App\Http\Requests\UpdateUser;
use App\Models\Post;
use App\Models\User as User_DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class User extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query=DB::table('users');
        if ($request->input('query') && $request->input('query')!="") {
            $query->where(function($query) use ($request) {
                // return $query;
                $query->where('name','like','%'.$request->input('query').'%');
                $query->orwhere('email','like','%'.$request->input('query').'%');
                $query->orwhere('mobile_no','like','%'.$request->input('query').'%');
                
            });
        }
        $query->whereNull('deleted_at');
        $users=$query->paginate(10);
        foreach ($users as $key => $user) {
            $user->id=Crypt::encrypt($user?->id);
        }
        return view('user.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewUser $request)
    {
        try {
            $user=new User_DB();
            $user->name=sanatize_name($request->input('name'));
            $user->email=$request->input('email');
            $user->mobile_no=$request->input('mobile_no');
            $user->password=$request->input('password');
            if ($user->save()) {
                return redirect('/users');
            }else{
                $message="Oops Something Went Wrong...";
                return view("errors.500",compact("message"));
            }
        } catch (Exception $e) {
            $message="Error Occured ".$e->getMessage()." On Line ".$e->getLine()." File".$e->getFile();
            return view("errors.500",compact("message"));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id=Crypt::decrypt($id);
        $user=User_DB::findOrFail($id);
        return view('user.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request, $id)
    {
        try {
            $id=Crypt::decrypt($id);
            $user=User_DB::where('id',$id)->update([
                'name'=>sanatize_name($request->input('name')),
                'email'=>$request->input('email'),
                'mobile_no'=>$request->input('mobile_no')
            ]);
            return redirect('/users');
        } catch (Exception $e) {
            $message="Error Occured ".$e->getMessage()." On Line ".$e->getLine()." File".$e->getFile();
            return view("errors.500",compact("message"));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $id=Crypt::decrypt($id);
            $isDestroyed=User_DB::where('id',$id)->delete();
            $delete_posts=Post::where('user_id',$id)->delete();
            DB::commit();
            return redirect('/users');
        } catch (\Throwable $e) {
            DB::rollBack();
            $message="Error Occured ".$e->getMessage()." On Line ".$e->getLine()." File".$e->getFile();
            return view("errors.500",compact("message"));
        }
    }
}
