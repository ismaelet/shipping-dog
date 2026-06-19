@use('App\Helpers\PreviewHelper')

@pushOnce('styles')
	<style>
		.cart-panel {
			display: grid;
			gap: 1.5rem;
			padding: 1.5rem;
		}

		#items_panel {
			display: grid;
			gap: 1rem;
		}

		#items .row {
			grid-template-columns: 7rem auto 1rem;
			align-items: center;
			gap: 1rem;
		}

		#items .row img {
			display: block;
			border-radius: var(--border-radius-400);
		}

		.art_name {
			font-size: 0.9rem;
			letter-spacing: 0.01em;
		}

		.order-list__description {
			padding-top: 3px;
			font-size: var(--font-size-300);
		}

		.price {
			margin-top: 10px;
			font-size: 0.9rem;
			letter-spacing: 0.03em;
		}

		.price-old {
			color: #999;
			text-decoration: line-through;
		}

		.price-promo {
			color: #bb0e0e;
		}

		.art_warn {
			margin-top: 5px;
			color: #b90002;
		}

		#items_panel .remove {
			opacity: 0.4;
			background-color: transparent;
			border: none;
			aspect-ratio: 1 / 1;
			cursor: pointer;
			transition: opacity 0.4s;
		}

		#items_panel .remove:hover {
			opacity: 1;
		}
	</style>
@endpushOnce

<section
	id="items"
	class="cart-panel surface"
>
	<x-heading.component>Articoli</x-heading.component>

	<div id="items_panel">

		@foreach ($order->order_items as $orderItem)
			<div class="row grid">
				<!--Image-->
				@if ($orderItem->product)
					<a
						href="{{ $orderItem->product ? $orderItem->product->url() : '' }}"
						class="img_cont"
					>
						<img
							src="{{ $orderItem->image() }}"
							class="img-fill"
						>
					</a>
				@else
					<div>
						<img
							src="{{ public_asset(PreviewHelper::PLACEHOLDER_IMAGE) }}"
							class="img-fill"
						>
					</div>
				@endif

				<!--Data-->
				<div class="art_data">
					<a href="{{ $orderItem->product ? $orderItem->product->url() : null }}">
						<h4 class="">{{ $orderItem->name() }}</h4>
					</a>

					<p class="text-strong">
						@if ($orderItem->discount())
							<span class="price-old">{{ $orderItem->grossPriceString() }}</span>
						@else
							{{ price($orderItem->price()) }}
						@endif

						@if ($orderItem->discount())
							<span class="text-error-500 font-bold">{{ price($orderItem->total_amount_after_discount) }}</span>
						@endif
					</p>

					<p class="order-list__description">
						{!! nl2br($orderItem->multilineDescription()) !!}
					</p>
				</div>

				@if (!$order->status)
					<button
						type="submit"
						class="remove icon-box text-neutral-700"
						formnovalidate="formnovalidate"
						formaction="{{ route('cart.remove', $orderItem->id) }}"
					>
						{!! embed_icon('close') !!}
					</button>
				@endif

			</div>

			@if (!$isCompleted)
				<livewire:cart.order-item-credit-button :$orderItem />
			@endif
		@endforeach

	</div>
</section>
