{% block main %}

<!--HEAD-->
<div id="slider">
	<div id="bgr" class="bgr" <?= background_image('bgr.jpg'); ?>></div>

	<div id="title_cont">
		<div id="title-box">
			<h1 id="main_title">FAQ</h1>
		</div>
	</div>
</div>

<!--BODY-->
<div class="main-wrap">

	<!--INFO: order_items-->
	<div id="section1" class="sections">
		<div class="title-box">
			<div class="title_line"></div>
			<h1 class="title">INFO ORDINI</h1>
		</div>

		<!--info-->
		<div class="info_box">
			<?php $i = 1; ?>
			<input type="checkbox" id="chck1<?= $i; ?>" class="info_chck">

			<label for="chck1<?= $i; ?>" class="info_lbl">
				<h1 class="info_title">
					<span class="info_ico icon-box">
						<?= embed_icon('info') ?>
					</span>
					<span class="info_title_txt">
						HO ORDINATO MA NON MI È ARRIVATA NESSUNA email DI CONFERMA
					</span>
				</h1>
			</label>

			<p class="info_txt">
				Se non ti è arrivata nessuna email di conferma dopo l'acquisto ti consigliamo di controllare nella cartella di posta indesiderata o spam, in quanto a volte viene filtrata automaticamente per sbaglio. Qualora non si riesca comunque a trovare l'email non preoccuparti: l'ordine comunque è stato registrato e il corriere GLS ti manderà in ogni caso l'email della conferma di spedizione nella serata del giorno in cui la merce verrà spedita, normalmente entro 1-5 giorni lavorativi dall'acquisto.
			</p>
		</div>

		<div id="mark2"></div>

		<!--info-->
		<div class="info_box">
			<?php $i++; ?>
			<input type="checkbox" id="chck1<?= $i; ?>" class="info_chck">

			<label for="chck1<?= $i; ?>" class="info_lbl">
				<h1 class="info_title">
					<span class="info_ico icon-box">
						<?= embed_icon('info') ?>
					</span>
					<span class="info_title_txt">
						CAMBI DI TAGLIA, RESI E RIMBORSI
					</span>
				</h1>
			</label>

			<p class="info_txt">
				È possibile eseguire un cambio taglia o un reso del capo, basta seguire la semplice <a class="link" href="<?= CLIENT_ROOT; ?>/refund">procedura online</a>. Nel caso di un cambio, le spese di spedizione del nuovo capo sono a carico del cliente. Vi consigliamo di controllare la lista taglie prima di procedere a un cambio o ad un nuovo acquisto. L'invio del nuovo capo avverrà entro un giorno lavorativo dalla restituzione di quello vecchio.
			</p>
		</div>
	</div>

	<!--INFO: SHIPPING-->
	<div id="section2" class="sections">
		<div class="title-box">
			<div class="title_line"></div>
			<h1 class="title">INFO SPEDIZIONI</h1>
		</div>

		<!--info-->
		<div class="info_box">
			<?php $i++; ?>
			<input type="checkbox" id="chck2<?= $i; ?>" class="info_chck">

			<label for="chck2<?= $i; ?>" class="info_lbl">
				<h1 class="info_title">
					<span class="info_ico icon-box">
						<?= embed_icon('info') ?>
					</span>
					<span class="info_title_txt">
						TEMPI DI SPEDIZIONE
					</span>
				</h1>
			</label>

			<p class="info_txt">
				Dal momento dell'acquisto l'ordine sarà in elaborazione per <strong>1-5 giorni lavorativi</strong>. Successivamente verrà spedito e, se il metodo di spedizione scelto è tramite corriere, il pacco verrà consegnato in <strong>1-2 giorni lavorativi</strong>. Se invece è stata scelta la spedizione tramite posta ordinaria i tempi saranno di <strong>3-8 giorni lavorativi</strong>. Per le spedizioni all'estero i tempi possono variare e sono specificati al momento dell'acquisto.

			</p>
		</div>

		<!--info-->
		<div class="info_box">
			<?php $i++; ?>
			<input type="checkbox" id="chck2<?= $i; ?>" class="info_chck">

			<label for="chck2<?= $i; ?>" class="info_lbl">
				<h1 class="info_title">
					<span class="info_ico icon-box">
						<?= embed_icon('info') ?>
					</span>
					<span class="info_title_txt">
						TRACCIAMENTO ORDINE
					</span>
				</h1>
			</label>

			<p class="info_txt">
				Riceverete una email dal corriere GLS con istruzioni e codice di tracciamento intorno alle <strong>20:00 del giorno in cui avverrà la spedizione</strong>, prevista entro 3 giorni lavorativi dal momento del pagamento.
			</p>
		</div>

		<div id="mark3"></div>

		<!--info-->
		<div class="info_box">
			<?php $i++; ?>
			<input type="checkbox" id="chck2<?= $i; ?>" class="info_chck">

			<label for="chck2<?= $i; ?>" class="info_lbl">
				<h1 class="info_title">
					<span class="info_ico icon-box">
						<?= embed_icon('info') ?>
					</span>
					<span class="info_title_txt">
						SPEDIZIONI IN GIACENZA
					</span>
				</h1>
			</label>

			<p class="info_txt">
				Nel caso ci sia un errore nell'indirizzo inserito, o nel caso non ci sia nessuno presente al momento della consegna per due giorni consecutivi allora il pacco andrà in giacenza. Vi contatteremo immediatamente per fissare un nuovo tentativo di consegna ma, se non riceveremo risposta entro 3 giorni, saremo costretti a chiedere il ritorno della merce.
			</p>
		</div>
	</div>

	<!--INFO: STORE-->
	<div id="section3" class="sections">
		<div class="title-box">
			<div class="title_line"></div>
			<h1 class="title">INFO VENDITE</h1>
		</div>

		<!--info-->
		<div class="info_box">
			<?php $i++; ?>
			<input type="checkbox" id="chck3<?= $i; ?>" class="info_chck">

			<label for="chck3<?= $i; ?>" class="info_lbl">
				<h1 class="info_title">
					<span class="info_ico icon-box">
						<?= embed_icon('info') ?>
					</span>
					<span class="info_title_txt">
						COME APRO UN NEGOZIO?
					</span>
				</h1>
			</label>

			<p class="info_txt">
				Aprire un negozio sul nostro sito è molto semplice, basta registrarsi, andare sulla <a href="<?= CLIENT_ROOT; ?>/sell">pagina per aprire uno store</a> e seguire la procedura, facile e veloce.
			</p>
		</div>

		<!--info-->
		<div class="info_box">
			<?php $i++; ?>
			<input type="checkbox" id="chck3<?= $i; ?>" class="info_chck">

			<label for="chck3<?= $i; ?>" class="info_lbl">
				<h1 class="info_title">
					<span class="info_ico icon-box">
						<?= embed_icon('info') ?>
					</span>
					<span class="info_title_txt">
						IL MIO NEGOZIO SARÀ ATTIVO FIN DA SUBITO?
					</span>
				</h1>
			</label>

			<p class="info_txt">
				Appena creato il negozio sarà già possibile cambiare tutti i dettagli a piacimento, come immagine di copertina, nome, descrizione, prezzi, ma sarà necessario caricare almeno un lavoro prima che il negozio possa essere visibile al pubblico.<br>
				Quanto ti senti pronto/a mandanci la richiesta a <a href="mailto:<?= config('custom.contacts.email.url') ?>"><?= config('custom.contacts.email.label') ?></a> e pubblicheremo il tuo negozio entro 24 ore.
			</p>
		</div>

		<!--info-->
		<div class="info_box">
			<?php $i++; ?>
			<input type="checkbox" id="chck3<?= $i; ?>" class="info_chck">

			<label for="chck3<?= $i; ?>" class="info_lbl">
				<h1 class="info_title">
					<span class="info_ico icon-box">
						<?= embed_icon('info') ?>
					</span>
					<span class="info_title_txt">
						GUADAGNI PROPOSTI
					</span>
				</h1>
			</label>

			<p class="info_txt">
				Per ogni vendita di un capo con stampato il proprio design verrà corrisposto una percentuale del prezzo di vendita come guadagno. Dopo aver creato un negozio è possibile modificare a piacere il prezzo degli articoli, modificando così i propri guadagni.
			</p>
		</div>

		<!--info-->
		<div class="info_box">
			<?php $i++; ?>
			<input type="checkbox" id="chck3<?= $i; ?>" class="info_chck">

			<label for="chck3<?= $i; ?>" class="info_lbl">
				<h1 class="info_title">
					<span class="info_ico icon-box">
						<?= embed_icon('info') ?>
					</span>
					<span class="info_title_txt">
						COME VERRÒ PAGATO?
					</span>
				</h1>
			</label>

			<p class="info_txt">
				Appena accumulato un guadagno minimo di <strong>25,00€</strong> sarà possibile richiedere il pagamento. A causa delle nuove normative riguardo ai pagamenti occasionali, il pagamento avverrà entro 7-10 giorni lavorativi dal momento della richiesta. Per poter ricevere il pagamento si deve disporre di un account <strong>PayPal<strong>. Nel caso non si abbia un account PayPal crearne uno è semplice, veloce e completamente gratuito. Una volta ricevuto il pagamento su PayPal sarà possibile usare l'importo direttamente, oppure spostarlo comodamente sulla propria carta di credito o conto corrente.
			</p>
		</div>
	</div>
</div>

{% end main %}