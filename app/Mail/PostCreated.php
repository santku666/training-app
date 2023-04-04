<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PostCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $title;
    private $author;
    private $from_mail_address;
    private $from_mail_name;
    public function __construct($body)
    {
        $this->title=array_key_exists('title',$body)?$body['title']:null;
        $this->author=array_key_exists('author',$body)?$body['author']:null;
        $this->from_mail_address=array_key_exists('from_mail_address',$body)?$body['from_mail_address']:null;
        $this->from_mail_name=array_key_exists('from_mail_name',$body)?$body['from_mail_name']:null;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address($this->from_mail_address,$this->from_mail_name),
            subject: 'Post Created',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'mail-layouts.new-post-created',
            with:[
                'title'=>$this->title,
                'author'=>$this->author
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
