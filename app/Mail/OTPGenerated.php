<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OTPGenerated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private int $code;
    private $from_address;
    private $from_name;
    public function __construct($body)
    {
        $this->code=array_key_exists('code',$body)?$body['code']:null;
        $this->from_address=array_key_exists('from_address',$body)?$body['from_address']:null;
        $this->from_name=array_key_exists('from_name',$body)?$body['from_name']:null;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'One Time Password For Email Verification',
            from:new Address($this->from_address,$this->from_name),
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
            view: 'mail-layouts.otp-generated',
            with:[
                'code'=>$this->code
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
