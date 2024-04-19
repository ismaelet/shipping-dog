<?php

namespace App\Mail;

use App\Models\Ecommerce\Cart;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusMail extends Mailable {

	use Queueable, SerializesModels;

	public Cart $cart;

	/**
	 * Create a new message instance
	 */
	public function __construct(Cart $cart) {
		$this->cart = $cart;
	}

	/**
	 * Generate the mail envelope
	 */
	public function envelope(): Envelope {
		return new Envelope(
			from: new Address(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME')),
			subject: 'Ordine confermato',
		);
	}

	/**
	 * Generate the mail content
	 */
	public function content(): Content {
		$info = 'Grazie per l\'acquisto su <a href="' . env('APP_URL') . '">' . env('APP_NAME') . '</a>. L\'ordine è stato ricevuto e sarà processato al più presto.';

		return new Content(
			view: 'emails.order-status',
			with: [
				'cart' => $this->cart,
				'title' => $this->subject,
				'info' => $info,
			],
		);
	}
}
