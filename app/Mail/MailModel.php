<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class MailModel extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $username;


    public function __construct($token, $username)
    {
        $this->token = $token;
        $this->username = $username;
    }

    public function build()
    {
        // Use the view 'emails.passwordrecovery' and pass the token and username to it
        return $this->view('emails.passwordrecovery')
                    ->with([
                        'token' => $this->token,
                        'username' => $this->username,
                    ])
                    ->subject('Password Recovery for Lend-A-Hand');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME')),
            subject: 'Lend-A-Hand Password Recovery',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.passwordrecovery',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
