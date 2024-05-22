<?php

namespace App\Mail;

use App\Helpers\EcommerceHelper;
use App\Models\Ecommerce\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrderStatusMail extends Mailable {

	use Queueable, SerializesModels;

	public $subject;
	public Order $order;

	/**
	 * Create a new message instance
	 */
	public function __construct(Order $order) {
		$this->order = $order;
		$this->subject = 'Aggiornamento dell\'ordine ' . $order->statusCode() . ': ' . $order->statusName();
	}

	/**
	 * Generate the mail envelope
	 */
	public function envelope(): Envelope {
		return new Envelope(
			from: new Address(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME')),
			replyTo: [
				new Address(config('custom.contacts.email.label'), env('MAIL_FROM_NAME')),
			],
			subject: $this->subject,
		);
	}

	/**
	 * Generate the mail content
	 */
	public function content(): Content {
		$content = $this->generateContent();

		if (!$content) {
			Log::error('OrderStatusMail: Status content not found', ['order' => $this->order, 'status' => $this->order->statusName()]);
		}

		return new Content(
			view: 'emails.order-status',
			with: [
				'order' => $this->order,
				'title' => $this->subject,
				'info' => $content,
			],
		);
	}

	/**
	 * ~ Helper: Generate Content
	 */
	private function generateContent(): string|false {
		switch ($this->order->status) {
			case EcommerceHelper::statusFromTag('cancelled'):
				return 'L\'ordine è stato annullato. Se non hai richiesto tu l\'annullamento, contatta il nostro servizio clienti a <a href="' . config('custom.contacts.email.url') . '">' . config('custom.contacts.email.label') . '</a>.';
			case EcommerceHelper::statusFromTag('received'):
				return 'L\'ordine è in fase di elaborazione del pagamento. Se entro un ora non riceverai una mail di conferma, conferma che il pagamento sia andato in porto.<br><br>In caso contrario puoi usare il bottone qui sotto per tornare al riepilogo dell\'ordine ed effettuarlo.<br><br>Se hai già pagato, ma l\'ordine continua a non essere confermato, contatta il nostro servizio clienti a <a href="' . config('custom.contacts.email.url') . '">' . config('custom.contacts.email.label') . '</a>.';
			case EcommerceHelper::statusFromTag('confirmed'):
				return 'Abbiamo confermato il pagameto dell\'ordine. I prodotti verranno spediti entro <span
				class="font-bold text-neutral-700">' . $this->order->shipping_method->minimumWorkingDays($this->order->hasLongDelivery()) . '-' . $this->order->shipping_method->maximumWorkingDays($this->order->hasLongDelivery()) . 'giorni lavorativi</span>.<br><br>Ti ringraziamo per aver scelto di acquistare da noi.';
			case EcommerceHelper::statusFromTag('shipped'):
				return 'L\'ordine è stato spedito e verrà consegnato entro <strong>48h larovative</strong>.';
			case EcommerceHelper::statusFromTag('delivered'):
				return 'L\'ordine è stato consegnato. Se per caso non lo avessi ricevuto, contatta il nostro servizio clienti a <a href="' . config('custom.contacts.email.url') . '">' . config('custom.contacts.email.label') . '</a>.';
			default:
				return false;
		}
	}
}
