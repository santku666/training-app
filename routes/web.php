<?php

use App\Http\Controllers\MailPreview;
use App\Http\Controllers\Post;
use App\Http\Controllers\User;
use App\Http\Controllers\UserAuth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () { #home route
    return view('home');
});

Route::middleware('guest')->resource('users',User::class);

Route::prefix('user')->controller(UserAuth::class)->group(function(){

    Route::middleware('guest')->group(function(){ # routes implementing middleware guest
        Route::get('login',function(){
            return view('user.account.login');
        });
        Route::post('login/perform','login_perform');
    });

    Route::get('logout','logout_perform');
    Route::prefix('posts')->middleware('auth')->controller(Post::class)->group(function(){ # routes for posts
        Route::get('/','index');
        Route::get('/create','create');
        Route::post('/','store');
        Route::delete('/{id}','destroy');
        Route::get('/{id}/view','show');
        
    });
    Route::middleware('auth')->controller(UserAuth::class)->group(function(){
        Route::get('verify-email',function(){
            return view('user.account.verify-email');
        });
    });
});
Route::prefix('mail-preview')->controller(MailPreview::class)->group(function(){ # mail preview routes for posts
    Route::get('new-post-created','new_post_created');
});

