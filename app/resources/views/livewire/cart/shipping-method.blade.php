<label class="ship_row interactive group">
	<x-checkbox
		type="radio"
		name="shipping_method_id"
		value="{{ $shippingMethod->id }}"
		:checked="$cart->shipping_method_id == $shippingMethod->id ? true : false"
		wire:input="selectShippingMethod({{ $shippingMethod->id }})"
	/>

	<div class="grid grid-cols-1 gap-0.5 py-4">
		<h4 class="text-lg">
			{{ $shippingMethod->name }}<br>
			<span class="date">{!! $shippingMethod->deliveryDateString(null, $cart->hasLongDelivery()) !!}</span>
		</h4>

		<p class="order-list__description">{!! $shippingMethod->desc !!}</p>
		<p class="text-lg font-semibold text-neutral-700">{{ $shippingMethod->priceString($cart->quantity) }}</p>
	</div>
</label>
