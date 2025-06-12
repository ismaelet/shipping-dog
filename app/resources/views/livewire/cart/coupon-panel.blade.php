<article class="cart-panel surface">
	<x-typography.component-heading>Codice sconto</x-typography.component-heading>

	<div class="grid grid-cols-1 gap-4">

		@if (!$coupon)
			<x-input
				type="text"
				wire:model.live.debounce.250ms="couponCode"
				placeholder="Codice sconto (opzionale)"
				value="{{ $couponCode }}"
			/>

			@error('couponCode')
				<x-alerts.error :$message />
			@enderror

			@if ($couponCode)
				<x-button wire:click="save">Applica</x-button>
			@endif
		@else
			<p class="">
				Codice usato: <span class="text-strong">{{ $coupon->code }}</span>
			</p>
			<x-button
				variant="secondary"
				color="danger"
				wire:click="remove"
			>
				Rimuovi
			</x-button>
		@endif

	</div>
</article>
