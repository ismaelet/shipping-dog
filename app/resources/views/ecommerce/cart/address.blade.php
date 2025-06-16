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
			id="left_panel"
			class="panel grid grid-cols-1 content-start gap-8"
		>

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
							<livewire:cart.shipping-credit-button />
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
			</section>
		</div>

		<div
			id="right_panel"
			class="panel grid grid-cols-1 content-start gap-8"
		>

			{{-- Order items --}}
			<livewire:cart.order-items-panel />

			{{-- Credit panel --}}
			<livewire:cart.credit-panel />

			{{-- Coupon panel --}}
			<livewire:cart.coupon-panel />

			{{-- Recap panel --}}
			<livewire:cart.recap-panel />

		</div>
	</form>
</x-layouts.cart>
