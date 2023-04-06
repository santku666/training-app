<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\PostCreated;

class SendPostCreatedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public string $title;
    public string $author;
    public string $from_address;
    public string $from_name;
    public string $to_address;
    public function __construct(string $title,string $author,string $from_address,string $from_name,string $to_address)
    {
        $this->title=$title;
        $this->author=$author;
        $this->from_address=$from_address;
        $this->from_name=$from_name;
        $this->to_address=$to_address;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       
        $body=[
            'title'=>$this->title,
            'author'=>$this->author,
            "from_address"=>$this->from_address,
            "from_name"=>$this->from_name
        ];
        
        Mail::to($this->to_address)->send(new PostCreated($body));
    }
}
