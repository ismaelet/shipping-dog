<div class="form-tbl">

	<div class="form-cell">

		<form method="POST" id="form" class="form" data-hroot="<?= CLIENT_ROOT ?>">

			<div class="form-head">
				<h1 class="form-title">Pagamento</h1>
			</div>

			<div class="form-body">
				<p class="txt-1rem mar-b-2 mar-t-1">Aggiungi i dati della tua carta qui sotto e poi seleziona <span class="txt-bold txt-c-title">Paga</span class="txt-bold txt-c-title">. Se vuoi cambiare metodo di pagamento puoi usare il pulsante <span class="txt-bold txt-c-title">Indietro</span class="txt-bold txt-c-title">.</p>
				<div class="sr-root">
					<div class="sr-main">
						<form id="payment-form" class="sr-payment-form">
							<div class="sr-combo-inputs-row">
								<div class="sr-input sr-card-element" id="card-element"></div>
							</div>
							<div class="sr-field-error" id="card-errors" role="alert"></div>

							<div class="grid gap1">
								<button type="button" id="submit" class="pbtn ora" disabled>
									<div id="spinner_cont">
										<div id="spinner"></div>
									</div>
									<span id="button-text">Paga <span id="amnt">0.00</span>€</span>
								</button>

								<div class="load-icon mar-t-2"></div>

								<a href="<?= CLIENT_ROOT; ?>/cart?step=4" class="sbtn">Indietro</a>
							</div>

						</form>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script src="<?= CLIENT_ROOT . $file = '/pages/scripts/cart/stripe_handler.js';
				echo '?v=' . filemtime(SERVER_ROOT . $file); ?>"></script>