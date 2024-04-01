<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Welcome extends Mailable {

	use Queueable, SerializesModels;

	public string $content;

	public function __construct(bool $registered = false) {
		$this->content = 'È con grande entusiasmo che vi do il benvenuto nel nostro spazio dedicato all\'apprendimento e alla crescita personale!<br><br>

		Mantenetevi Aggiornati:<br><br>
		
		Sono qui per accompagnarvi in un percorso educativo ricco di scoperte e successi. Mantenetevi aggiornati su tutte le novità, i corsi in arrivo e le risorse aggiuntive che renderanno ogni passo del vostro percorso educativo appagante e stimolante.<br><br>
		
		<span style="font-size: 1.2em; font-weigth: 600;>
			Risorse Gratuite per Voi e i Vostri Bambini:
		</span>
		<br><br>
		
		Per festeggiare la vostra presenza, abbiamo preparato un esclusivo fascicolo "Tabelline disegnate" creato per arricchire la vostra esperienza di apprendimento e quella dei vostri piccoli.<br><br>';

		if (!$registered) {
			$this->content .= 'Crea il Tuo Profilo e Accedi a Materiale Esclusivo:<br><br>
		
		Se desiderate personalizzare ulteriormente la vostra esperienza, create un profilo e accedete a nuovi materiali gratuiti che stiamo creando e caricando appositamente per voi! Troverete tante risorse tra cui videocorsi, struemtni e informazioni utili.<br><br>';
		}

		$this->content .= 'Il Vostro Impegno Fa la Differenza:<br><br>
		
		Ricordate, il semplice fatto che abbiate la volontà di informarvi e di attivarvi per migliorare lo stile di apprendimento dei vostri ragazzi sta già facendo la differenza. Siamo qui per supportarvi in ogni passo di questo viaggio educativo.<br><br>
		
		Grazie per aver scelto di far parte della nostra comunità di apprendimento. Siamo ansiosi di condividere questa avventura con voi!<br><br>
		
		
		Con entusiasmo,<br>
		Dott.ssa Sperandio Sara<br>
		www.studiocollaborando.com';
	}

	public function build() {
		$attachment = public_path('uploads/attachments/Tabelline Disegnate.pdf');

		return $this
			->subject("Benvenuti nel Nostro Spazio di Apprendimento!")
			->view('emails.default-email')
			->attach($attachment);
	}
}
