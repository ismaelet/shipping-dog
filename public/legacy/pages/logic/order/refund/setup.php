<?php

use App\Models\Ecommerce\ChangeRequest;

$error = false;
$changeRequestStep = 1;

/**
 * Check if the change request exists
 */
if (isset($_SESSION['changeRequestId']) && $viewStep > 1) {

	// Get the change request
	$changeRequestId = $_SESSION['changeRequestId'];
	$changeRequest = ChangeRequest::find($changeRequestId);
	$changeRequestStep = $changeRequest->step;

	// Get the changes
	$changes = $changeRequest->changes;

	$actualChanges = $changes->filter(function ($item) {
		return $item->is_change == 1;
	});

	$refunds = $changes->filter(function ($item) {
		return $item->is_change == 0;
	});

	// Set the instructions
	if (count($actualChanges)) {
		$instructions = "
			<strong>1.</strong> Non appena terminerai la prodecura di cambio metteremo in produzione il nuovo capo, che sarà spedito in 1-5 giorni lavorativi tramite <strong>corriere GLS</strong>.<br><br>
			<strong>2.</strong> Quando il nuovo capo sarà spedito riceverai una e-email da parte del corriere la data di consegna stimata.
			<strong>3.</strong> Per rendere il capo vecchio è sufficiente darlo al corriere quando ti consegnerà il capo nuovo.
		";

		$notes = '';

		if ($viewStep == 3) {
			$notes .= "<strong>-</strong> Il costo di questa proceudra corrisponde al costo della nuova spedizione ovvero <strong>8,00€</strong>.<br><br>";
		}

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

/**
 * Redirectto the correct step
 */
if ($viewStep > 1 && $changeRequestStep != $viewStep) {
	redirect('/refund?step=' . $changeRequestStep);
}

/**
 * Cancel the change request
 */
if (isset($_POST['cancel'])) {
	foreach ($changeRequest->changes as $change) {
		$change->delete();
	}

	$changeRequest->delete();

	unset($_SESSION['changeRequestId']);

	redirect('/refund');
}

/**
 * Go back to the previous step
 */
function return_to_previous_step(ChangeRequest $changeRequest): void {
	$changeRequest->step = $changeRequest->step - 1;
	$changeRequest->save();

	redirect('/refund?step=' . $changeRequest->step);
}
