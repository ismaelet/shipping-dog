<?php

use Illuminate\Support\Facades\Auth;
use Legacy\Classes\Ecommerce\ViewManager;
use Legacy\Classes\ViewManager\Ecommerce\CartFlowBar;
use Legacy\Classes\ViewManager\Ecommerce\CartRecap;
use Legacy\View\Components\Form\Input;

$cart = cart();

?>

<x-layouts.cart :title="$title . ' | Carrello'">

	<div
		class="grid-flow"
		data-spacing="700"
	>

		@if (cart()->shipping_method->isLocalDelivery())
			<p class="center-content input-warning">Hai scelto di ritirare il capo in sede, quindi l'ordine NON verrà spedito, ma
				dovrai ritirarlo nella nostra sede in Piazza Scopoli, 9, 38033 Cavalese (TN)</p>
		@endif

		<div class="">

			<form
				id="pay_panel"
				class="grid items-start gap-8 lg:grid-cols-2"
				method="POST"
			>
				@csrf

				<div class="cart-panel surface content-start">
					<x-typography.component-heading class="title">Pagamento</x-typography.component-heading>
					@foreach ($paymentMethods as $paymentMethod)
						<label class="chck_item grid grid-cols-[max-content_1fr] items-center gap-4 sm:grid-cols-[5rem_1fr]">
							<x-checkbox
								type="radio"
								class=""
								name="payment_method_id"
								:value="$paymentMethod['id']"
								:checked="cart()->payment_method_id == $paymentMethod['id']"
							/>

							<div class="grid grid-cols-1 gap-2 py-8">
								<h4 class="text-2xl">{{ $paymentMethod['name'] }}</h4>

								@if (file_exists(base_path() . '/resources/images/cart/pay_' . $paymentMethod['tag'] . '.png'))
									<img
										class="max-h-18"
										src="{{ Vite::image('cart/pay_' . $paymentMethod['tag'] . '.png') }}"
									/>
								@endif

								<p class="pay_desc">{{ $paymentMethod['desc'] }}</p>
							</div>
						</label>
					@endforeach
				</div>

				<!-- Recap -->
				<livewire:cart.recap-panel />
			</form>
		</div>
	</div>

</x-layouts.cart>
