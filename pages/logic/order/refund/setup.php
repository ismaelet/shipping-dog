<?php

$error = false;
$status = 1;

if (isset($_SESSION['refID']) && $stepId > 1) {

	$id = $_SESSION['refID'];

	$row = DB::row('status, processed FROM refunds WHERE cartID=?', [$id]);
	$status = $row['status'];
	$processed = $row['processed'];

	if (!$status) {

		unset($_SESSION['refID']);
		redirect('/refund');
	}

	$res_sel = DB::select('* FROM refunds_ord WHERE cartID=?', [$id]);

	$chan = DB::exists('refunds_ord WHERE cartID=? AND type=1', [$id]);
	$refn = DB::exists('refunds_ord WHERE cartID=? AND type=2', [$id]);

	if ($chan) {
		$instructions = "
			<strong>1.</strong> Non appena terminerai la prodecura di cambio metteremo in produzione il nuovo capo, che sarà spedito in 1-5 giorni lavorativi tramite <strong>corriere GLS</strong>.<br><br>
			<strong>2.</strong> Quando il nuovo capo sarà spedito riceverai una e-email da parte del corriere la data di consegna stimata.
			<strong>3.</strong> Per rendere il capo vecchio è sufficiente darlo al corriere quando ti consegnerà il capo nuovo.
		";

		$notes = '';

		if ($stepId == 3)  $notes .= "<strong>-</strong> Il costo di questa proceudra corrisponde al costo della nuova spedizione ovvero <strong>8,00€</strong>.<br><br>";

		$notes .= "
			<strong>-</strong> Gli articoli da rendere possono essere messi in una scatola o in una busta. Non è necessario usare la confezione originale.<br><br>
		";
	} else {

		$instructions = "
			<strong>1.</strong> Spedisci il pacco con il reso con il metodo di spedizione che preferisci (tramite Poste o qualsiasi corriere) all'indirizzo:<br><br>

			Shipping Dog SNC<br>
			Piazza Scopoli, 9<br>
			38033 Cavalese (TN)<br><br>

			<strong>2.</strong> Il costo degli articoli sarà rimborsato entro <strong>un giorno lavorativo</strong> dalla ricezione del reso.
		";

		$notes = "
			<strong>-</strong> Gli articoli da rendere possono essere messi in una scatola o in una busta. Non è necessario usare la confezione originale.<br><br>
			<strong>-</strong> Verrà rimborsato <strong>SOLO</strong> il costo degli articoli, non delle spese di spedizione pagate al momento dell'acquisto o per effettuare il reso.<br><br>
			<strong>-</strong> Se vuoi effettuare il reso perché il prodotto presenta qualche difetto o non corrrisponde a ciò che hai ordinato, invece di seguire questa procedura contattaci a <a class='link' href='" . sys_config('contact-email-link') . "'>" . sys_config('contact-email') . "</a> e cercheremo di risolvere il problema.
		";
	}
}

if ($stepId > 1 && $status != $stepId) redirect('/refund?step=' . $status);
