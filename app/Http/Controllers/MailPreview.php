<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MailPreview extends Controller
{
    public function __construct()
    {
        
    }

    /**
     * ------------------------------
     *  preview method for new post created --author
     * -----------------------------
     * */ 
    public function new_post_created()
    {
        return view('mail-layouts.new-post-created');
    }
}
