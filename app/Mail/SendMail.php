<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        // If you need to pass data to the view, you can define it here
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Mail from My Application') // Set the subject of the email
                    ->view('emails.otp'); // Set the view for the email content
    }
}
