@use('App\Services\Files\ProductPreviewProcessor')

<div class="grid gap-4">
	@if (!$order || $order->order_items->isEmpty())
		<p class="text-center">Il carrello è vuoto</p>
	@else
		@foreach ($order->order_items as $orderItem)
			<div
				class="grid grid-cols-[7rem_auto_1rem] items-center gap-4"
				wire:key="order-item-{{ $orderItem->id }}"
			>
				<!--Image-->
				@if ($orderItem->product)
					<a
						href="{{ $orderItem->product ? $orderItem->product->url() : '' }}"
						class=""
					>
						<img
							src="{{ $orderItem->image() }}"
							class="rounded"
						>
					</a>
				@else
					<div>
						<img
							src="{{ public_asset(ProductPreviewProcessor::PLACEHOLDER_IMAGE) }}"
							class="rounded"
						>
					</div>
				@endif

				<!--Data-->
				<div class="">
					<a href="{{ $orderItem->product ? $orderItem->product->url() : null }}">
						<h4 class="">{{ $orderItem->name() }}</h4>
					</a>

					@if ($orderItem->total_discount > 0)
						<p class="font-semibold line-through opacity-50">{{ price($orderItem->total_amount_before_discount) }}</p>
						<p class="text-error-500 font-bold">{{ price($orderItem->total_amount_after_discount) }}</p>
					@else
						<p class="text-neutral-heading font-semibold">{{ price($orderItem->total_amount_after_discount) }}</p>
					@endif

					<p class="mt-1 text-sm">
						{!! nl2br($orderItem->multilineDescription()) !!}
					</p>
				</div>

				@if (!$order->status)
					<button
						type="button"
						class="remove hover:text-error-400 text-neutral-heading w-4 cursor-pointer opacity-70 transition-all hover:opacity-100"
						wire:click="removeItem({{ $orderItem->id }})"
						wire:loading.attr="disabled"
						aria-label="Rimuovi articolo"
					>
						<x-icon-close />
					</button>
				@endif

			</div>

			@if (!$isCompleted)
				<livewire:cart.order-item-credit-button
					:order-item-id="$orderItem->id"
					:key="'order-item-credit-button-' . $orderItem->id"
				/>
			@endif
		@endforeach
	@endif

</div>
