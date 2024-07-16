<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InviteMail extends Mailable
{
    use Queueable, SerializesModels;

   
    public $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You are invited'
        );
    }
    public function build()
    {
        return $this->view('emails.invites')
                    ->subject('You are invited')
                    ->with([
                        'link' => $this->link,
                    ]);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invites',
            with: [
                'link' => $this->link,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
