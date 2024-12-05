<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;
    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->subject('OTP for New Device Login')  // Set the subject of the email
        ->view('emails.otp') // Set the view for the email content
        ->with([
            'otp' => $this->otp, // Pass the OTP to the view
        ]);
    }
}
