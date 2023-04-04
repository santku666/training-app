<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUser;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
