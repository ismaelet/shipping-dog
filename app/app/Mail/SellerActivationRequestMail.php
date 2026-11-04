<?php

namespace App\Mail;

use App\Models\Ecommerce\Seller;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SellerActivationRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Property: The email body text
     */
    public string $text = '';

    /**
     * Create a new message instance
     */
    public function __construct(public Seller $seller)
    {
        $this->text = 'Hai ricevuto la richiesta di attivazione dello shop <strong>'.$this->seller->name.'</strong> ('.$this->seller->user->email.').';
    }

    /**
     * Get the message envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            replyTo: [
                new Address($this->seller->user->email, $this->seller->name),
            ],
            subject: 'Richiesta attivazione shop',
        );
    }

    /**
     * Get the message content definition
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.seller-activation-request',
        );
    }

    /**
     * Get the attachments for the message
     */
    public function attachments(): array
    {
        return [];
    }
}
