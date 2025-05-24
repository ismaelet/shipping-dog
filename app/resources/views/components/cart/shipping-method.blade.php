<label class="ship_row interactive group">
	<x-inputs.checkbox
		type="radio"
		class="js-shipping-method js-cart-shipping-price-string"
		name="shipping_method_id"
		:value="$shippingMethod->id"
		:checked="$cart->shipping_method_id == $shippingMethod->id ? true : false"
		:required="true"
		data-shipped="{{ $shippingMethod->isShipped() ? 1 : 0 }}"
		data-cart-id="{{ $cart->id }}"
		data-shipping-method-id="{{ $shippingMethod->id }}"
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
