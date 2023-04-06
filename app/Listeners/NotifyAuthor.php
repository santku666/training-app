<?php

namespace App\Listeners;

use App\Events\PostCreated;
use App\Jobs\SendPostCreatedEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class NotifyAuthor
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\PostCreated  $event
     * @return void
     */
    public function handle(PostCreated $event)
    {
        // dispatch job
        $title=$event?->post?->title;
        $author=Auth::user()->name;
        $from_address=env('MAIL_FROM_ADDRESS');
        $from_name=env('MAIL_FROM_NAME');
        $to_address=Auth::user()->email;
        $dispatch=SendPostCreatedEmail::dispatch($title,$author,$from_address,$from_name,$to_address);
    }
}
