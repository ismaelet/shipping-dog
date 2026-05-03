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
				<x-heading.component>Metodo di spedizione</x-heading.component>

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


				<!-- Shipping method -->
				<div>

					@foreach ($shippingMethods as $shippingMethod)
						<livewire:cart.shipping-method
							:$cart
							:$shippingMethod
						/>

						@if ($cart->shipping_method_id == $shippingMethod->id)
							<livewire:cart.shipping-credit-button />
						@endif
					@endforeach

				</div>
			</section>

			{{-- Shipping address --}}
			<livewire:cart.shipping-address :$cart />
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
