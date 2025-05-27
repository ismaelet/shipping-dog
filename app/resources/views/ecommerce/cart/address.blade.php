<?php

use Illuminate\Support\Facades\Auth;
use Legacy\Classes\Ecommerce\ViewManager;
use Legacy\Classes\ViewManager\Ecommerce\CartFlowBar;
use Legacy\Classes\ViewManager\Ecommerce\CartRecap;
use Legacy\View\Components\Form\Input;

$cart = cart();

?>

<x-layouts.cart :title="$title . ' | Carrello'">
	<form
		id="content"
		method="POST"
	>
		@csrf

		<div
			method="POST"
			id="left_panel"
			class="panel grid grid-cols-1 gap-8"
		>
			@csrf

			<!-- Country -->
			<section class="cart-panel surface">
				<x-typography.component-heading>Metodo di spedizione</x-typography.component-heading>

				<p class="bott_txt">
					@if ($cart->hasLongDelivery())
						Il tuo ordine contiene un capo in <span class="txt-red font-semibold">preordine</span>. Se vuoi un <strong>tempo
							di consegna minore</strong>, ti consigliamo di selezionare solo magliette, felpe o accessori e con colore
						bianco, nero o grigio melange.<br>Se invece ti va bene la data di consegna indicata qui sotto, puoi lasciare
						l'ordine così com'è.<br><br>
					@endif

					La data di consegna prevista comprende, oltre al tempo di spedizione, <strong>1-5 giorni lavorativi</strong> di
					elaborazione dell'ordine.
				</p>

				<!-- <p class="error-warning mt-4">A del grande afflusso di ordini e della pressione sui corrieri durante questo periodo, NON garantiamo la consegna per Natale per nessun ordine effettuato dopo l'12 dicembre e ci potrebbero essere ritardi, anche consistenti, rispetto alla data di consegna stimata. Le spedizioni riprenderanno regolarmente a partire dal 26 dicembre. Grazie per la comprensione.</p> -->


				<!-- Shipping method -->
				<div>

					@foreach ($shippingMethods as $shippingMethod)
						<x-cart.shipping-method
							:$shippingMethod
							:$cart
						/>
						@if ($cart->shipping_method_id == $shippingMethod->id)
							@if (
								$shippingMethod->price($cart->quantity) &&
									!$cart->coupon &&
									!$cart->credit_ship &&
									Auth::check() &&
									Auth::user()->seller &&
									Auth::user()->seller->credit >= $shippingMethod->price($cart->quantity))
								<button
									class="use-credit-btn credit-btn btn btn--secondary button--full-width mar-b-2"
									name="ship_credit"
								>
									{!! embed_icon('dollar') !!}
									Usa il tuo credito
								</button>
							@elseif ($cart->credit_ship)
								<button
									class="no-credit-btn credit-btn btn btn--secondary button--full-width mar-b-2"
									name="ship_no_credit"
								>
									{!! embed_icon('close') !!}
									Non usare il credito
								</button>
							@endif
						@endif
					@endforeach

				</div>
			</section>

			<!-- Shipping address -->
			<section
				id="address"
				class="address-form cart-panel surface <?= $cart->shipping_method->isLocalDelivery() ? 'address-form--local-delivery' : '' ?>"
			>
				<x-typography.component-heading>Indirizzo</x-typography.component-heading>

				<?php if ($error == 'address') { ?>
				<p class="text-error">* tutti campi sono necessari</p>
				<?php } ?>

				<div class="gap1 grid">

					<x-input
						name="full_name"
						placeholder="Nominativo di spedizione"
						:value="$address->full_name ?? ''"
						:required="true"
						:trim="true"
					/>

					<div
						class="field <?= $cart->shipping_method->isLocalDelivery() ? 'hidden' : '' ?> grid gap-[inherit] md:grid-cols-2"
						data-shipping-field
					>
						<input
							type="text"
							id="inp_addr"
							class="input delivery-input"
							name="address"
							placeholder="Indirizzo"
							value="<?= $address->address ?? '' ?>"
							maxlength="255"
							autocomplete="address-line1"
							<?= !$cart->shipping_method->isLocalDelivery() ? 'required' : '' ?>
						>

						<input
							type="text"
							id="inp_num"
							class="input delivery-input"
							name="number"
							placeholder="Numero civico"
							value="<?= $address->number ?? '' ?>"
							maxlength="10"
							<?= !$cart->shipping_method->isLocalDelivery() ? 'required' : '' ?>
						>
					</div>

					<div
						data-shipping-field
						class="<?= $cart->shipping_method->isLocalDelivery() ? 'hidden' : '' ?>"
					>
						<input
							type="text"
							class="input delivery-input w-full"
							name="city"
							placeholder="Città"
							value="<?= $address->city ?? '' ?>"
							maxlength="255"
							autocomplete="address-level2"
							<?= !$cart->shipping_method->isLocalDelivery() ? 'required' : '' ?>
						>
					</div>

					<div
						class="field <?= $cart->shipping_method->isLocalDelivery() ? 'hidden' : '' ?> grid gap-[inherit] md:grid-cols-2"
						data-shipping-field
					>
						<input
							type="text"
							class="input delivery-input"
							name="zip"
							placeholder="Codice postale"
							value="<?= trim($address->zip ?? '') ?>"
							maxlength="5"
							autocomplete="postal-code"
							<?= !$cart->shipping_method->isLocalDelivery() ? 'required' : '' ?>
						>

						<select
							name="region"
							class="input delivery-input"
							placeholder="Provincia"
							<?= !$cart->shipping_method->isLocalDelivery() ? 'required' : '' ?>
						>
							<option
								value=""
								disabled
								<?= empty($address) ? 'selected' : '' ?>
							>Provincia</option>

							<?php foreach ($provinces as $province) { ?>
							<option
								value="<?= $province->tag ?>"
								<?= $address?->region == $province->tag ? 'selected' : '' ?>
							><?= $province->name ?> (<?= $province->tag ?>)</option>
							<?php } ?>

						</select>
					</div>

					<input
						type="email"
						class="input"
						name="email"
						placeholder="Indirizzo e-mail"
						value="<?= $address->email ?? '' ?>"
						maxlength="50"
						required
						autocomplete="email"
					>
					<input
						type="text"
						class="input js-input-no-space"
						name="phone"
						placeholder="Numero di telefono"
						value="<?= $address->phone ?? '' ?>"
						maxlength="30"
						autocomplete="tel"
						required
					>

					<div
						class="<?= $cart->shipping_method->isLocalDelivery() ? 'hidden' : '' ?>"
						data-shipping-field
					>
						<textarea
						 class="input w-full"
						 name="courier_notes"
						 placeholder="Note per il corriere (opzionale)"
						 maxlength="250"
						><?= $cart->courier_notes ?? '' ?></textarea>
					</div>

				</div>

				<button
					type="submit"
					id="submit"
					name="submit"
					class="hidden"
				></button>
			</section>
		</div>

		<div
			id="right_panel"
			class="panel grid grid-cols-1 content-start gap-8"
		>

			<!-- Order items -->
			<x-cart.order-list />

			@if (!$cart->usedOrderItemsCredit())
				<!-- Discount code -->
				<section
					id="code"
					class="cart-panel surface"
				>
					<x-typography.component-heading>Codice sconto</x-typography.component-heading>
					<div
						id="code_cont"
						class="grid grid-cols-1 gap-4"
						method="POST"
						action="#code-anch"
					>

						@if (!$cart->coupon?->id)
							<input
								type="text"
								id="code-inp"
								name="code"
								class="input wide"
								placeholder="Codice sconto (opzionale)"
								onFocus="$('.err').hide()"
							>
							@if ($error == 'coupon-invalid')
								<p class="error">Il codice inserito non esiste</p>
							@endif
							@if ($error == 'coupon-expired')
								<p class="error">Il codice inserito è scaduto</p>
							@endif
							@if ($error == 'coupon-not-active')
								<p class="error">Il codice inserito non è attivo</p>
							@endif
							@if ($error == 'coupon-not-logged')
								<p class="error">Devi essere registrato per poter usare questo codice</p>
							@endif
							@if ($error == 'coupon-used')
								<p class="error">Hai già usato il codice inserito</p>
							@endif

							<x-button
								type="submit"
								id="add_code"
								name="add_code"
								class="hidden"
								formnovalidate
							>Aggiungi</x-button>
						@else
							<p class="code_txt">
								Codice usato: <span class="code txt-c1">{{ $cart->coupon->code }}</span>
							</p>
							<x-button
								type="submit"
								name="del_code"
								id="code_remove"
								formnovalidate
							>Rimuovi</x-button>
						@endif

					</div>
				</section>
			@endif

			@if (is_seller() && !$cart->coupon?->id && $cart->user->seller->credit)
				<!-- Used credit panel -->
				<section
					id="credit-section"
					class="cart-panel surface"
				>
					<x-typography.component-heading>Credito</x-typography.component-heading>

					<p>Per ogni vendita realizzata ottieni un <strong>10% di bonus</strong> sui guadagni, che puoi usare per pagare le
						spese di spedizione oppure per comprare articoli del tuo negozio.<br><br>Questo credito non può essere usato
						insieme ad un codice sconto ed è possibile usarlo solo se si ha abbastanza credito da coprire l'intero prezzo del
						prodotto.</p>

					<p class="txt-med text-center">Hai usato <strong>{{ formatc($legacyCart->usedCredit) }} € /
							{{ formatc($legacyCart->customer->credit) }} €</strong></p>

					<div class="total-credit">
						<div
							class="used-credit"
							style="width: {{ round(($legacyCart->usedCredit / $legacyCart->customer->credit) * 100) }}%"
						></div>
					</div>


				</section>
			@endif

			<!-- Recap -->
			<x-cart.recap-panel />

		</div>
	</form>
</x-layouts.cart>
