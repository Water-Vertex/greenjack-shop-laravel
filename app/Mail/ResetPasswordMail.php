<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function build()
    {
        return $this->subject('Reset Your Password - ' . config('app.name'))
                    ->view('emails.reset-password')
                    ->with([
                        'resetUrl' => route('customer.reset-password', [
                            'token' => $this->token,
                            'email' => $this->email
                        ]),
                        'email' => $this->email,
                        'appName' => config('app.name'),
                    ]);
    }
}