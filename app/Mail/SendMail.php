<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details; // Store the details passed from the controller

    /**
     * Create a new message instance.
     *
     * @param array $details
     */
    public function __construct($details)
    {
        $this->details = $details; // Store the details
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->details['title']) // Set the subject dynamically from the details
                    ->view('emails.otp') // Set the view for the email content
                    ->with('details', $this->details); // Pass the details to the view
    }
}
