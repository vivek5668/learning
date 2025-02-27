<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailToStudent extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;

    public $message;

    public function __construct($subject, $message)
    {
        $this->subject = $subject;
        $this->message = $message;
        // dd($this->message);
    }

    public function build()
    {
        return $this->subject($this->subject)
            ->view('emails.student')
            ->with([
                'subject1' => $this->subject,
                'messageq' => $this->message,
            ]);
    }
}
