<?php

namespace App\Mail;

use App\Models\Ecommerce\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusMail extends Mailable {

	use Queueable, SerializesModels;

	public Order $order;

	/**
	 * Create a new message instance
	 */
	public function __construct(Order $order) {
		$this->order = $order;
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
				'cart' => $this->order,
				'title' => $this->subject,
				'info' => $info,
			],
		);
	}
}
