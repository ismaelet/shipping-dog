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
				<livewire:cart.shipping-methods
					:$cart
					:$shippingMethods
				/>
			</section>

			{{-- Shipping address --}}
			<livewire:cart.shipping-address :$cart />
		</div>

		<div
			id="right_panel"
			class="panel grid grid-cols-1 content-start gap-8"
		>

			{{-- Order items --}}
			<section
				id="items"
				class="cart-panel surface"
			>
				<x-heading.component>Articoli</x-heading.component>

				<livewire:cart.order-items-list :order="$cart" />
			</section>

			{{-- Credit panel --}}
			<livewire:cart.credit-panel />

			{{-- Coupon panel --}}
			<livewire:cart.coupon-panel />

			{{-- Recap panel --}}
			<livewire:cart.recap-panel />

		</div>
	</form>
</x-layouts.cart>
