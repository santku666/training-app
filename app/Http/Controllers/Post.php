<?php

namespace App\Http\Controllers;

use App\Events\PostCreated;
use App\Http\Requests\NewPost;
use App\Models\Post as ModelsPost;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Post extends Controller
{
    public function __construct()
    {
        # code...
    }

    /**
     * ###########################
     *  method to get all the posts
     * ###########################
     * */ 
    public function index(Request $request)
    {
        $query=DB::table('posts')->select("id","title","description",DB::raw("DATE_FORMAT(created_at,'%d/%m/%Y') AS created_at"))->where('user_id',Auth::user()->id)->whereNull('deleted_at');
        if ($request->input('query') && $request->input('query')!="") {
            $query->where('title','like','%'.$request->input('query').'%');
        }
        $posts=$query->paginate(10);
        return view('user.posts.index',compact('posts'));
    }
    /**
     * ###########################
     *  method to load create post page
     * ###########################
     * */ 
    public function create()
    {
        return view('user.posts.add');
    }

    /**
     * ###########################
     *  method to store the posts
     * ###########################
     * */ 
    public function store(NewPost $request)
    {
        try {
            $post=new ModelsPost();
            $post->title=$request->input('title');
            $post->description=$request->input('description');
            $post->user_id=Auth::user()->id;
            if ($post->save()) {
                event(new PostCreated($post->id));
                return redirect('/user/posts')->with('success','a new post with title'.$request->input('title')." is been created");
            } else {
                $message="Oops Something went wron {__LINE__}";
                return view("errors.500",compact("message"));
            }
            
        } catch (Exception $e) {
            $message="Error Occured ".$e->getMessage()." On Line ".$e->getLine()." File".$e->getFile();
            return view("errors.500",compact("message"));
        }
    }
    /**
     * ###########################
     *  method to delete a post
     * ###########################
     * */ 
    public function destroy($id)
    {
        $post=ModelsPost::where('id',$id)->first();
        if (Auth::user()?->id === $post?->user_id) { # check this post belongs to the user
            $delete=ModelsPost::where('id',$id)->delete();
            return redirect('/user/posts')->with('success','Post with title'.$post?->title.' has been removed');
        }
    }
    
    /**
     * ###########################
     *  method to delete a post
     * ###########################
     * */ 
    public function show($id)
    { 
        $post=ModelsPost::where('id',$id)->with('user')->first();
        if (Auth::user()?->id === $post?->user_id) {
            return view('user.posts.view',compact('post'));
        }
    }
}
