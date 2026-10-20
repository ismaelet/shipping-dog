<div role="radiogroup">
	@foreach ($shippingMethods as $shippingMethod)
		<div
			class="ship_row interactive group"
			wire:key="shipping-method-{{ $shippingMethod->id }}"
			wire:click="selectShippingMethod({{ $shippingMethod->id }})"
			wire:keydown.enter="selectShippingMethod({{ $shippingMethod->id }})"
			wire:keydown.space.prevent="selectShippingMethod({{ $shippingMethod->id }})"
			tabindex="0"
			role="radio"
			aria-checked="{{ $selectedShippingMethodId == $shippingMethod->id ? 'true' : 'false' }}"
		>
			<x-checkbox
				type="radio"
				name="shipping_method_id"
				value="{{ $shippingMethod->id }}"
				:checked="$selectedShippingMethodId == $shippingMethod->id"
				wire:model.live.number="selectedShippingMethodId"
				tabindex="-1"
			/>

			<div class="grid grid-cols-1 gap-0.5 py-4">
				<h4 class="text-lg">
					{{ $shippingMethod->name }}<br>
					<span class="date">
						{!! $shippingMethod->deliveryDateString(null, $hasLongDelivery) !!}
					</span>
				</h4>

				<p class="order-list__description">{!! $shippingMethod->desc !!}</p>

				<p class="text-neutral-heading text-lg font-semibold">
					{{ $shippingMethod->priceString($cart->quantity) }}
				</p>
			</div>
		</div>

		@if ($selectedShippingMethodId == $shippingMethod->id)
			<livewire:cart.shipping-credit-button wire:key="shipping-credit-button-{{ $shippingMethod->id }}" />
		@endif
	@endforeach
</div>
