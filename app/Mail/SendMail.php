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
    public $details; // Store the details passed from the controller

    /**
     * Create a new message instance.
     *
     * @param array $details
     */
    public function __construct($details)
    {
        $this->details = $details; // Store the details
        $this->otp = Str::random(6); // You can adjust the length and logic as needed
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your OTP Code')  // Set the subject of the email
        ->view('emails.otp') // Set the view for the email content
        ->with([
            'otp' => $this->otp, // Pass the OTP to the view
        ]);
    }
}
