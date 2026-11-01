<div role="radiogroup" class="grid grid-cols-1 gap-4">
    @foreach ($shippingMethods as $shippingMethod)
        <div class="border-border-400 not-last:border-b not-last:pb-4">
            <div
                class="group flex cursor-pointer items-center gap-4"
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

                <div class="">
                    <x-heading variant="article-small">{{ $shippingMethod->name }}</x-heading>
                    <p class="mt-1">{!! $shippingMethod->deliveryDateString(null, $hasLongDelivery) !!}</p>
                    <p class="">{!! $shippingMethod->desc !!}</p>
                    <p class="text-heading-400 mt-2 font-semibold">
                        {{ $shippingMethod->priceString($cart->quantity) }}
                    </p>
                </div>
            </div>

            @if ($selectedShippingMethodId == $shippingMethod->id)
                <livewire:cart.shipping-credit-button wire:key="shipping-credit-button-{{ $shippingMethod->id }}" />
            @endif
        </div>
    @endforeach
</div>
