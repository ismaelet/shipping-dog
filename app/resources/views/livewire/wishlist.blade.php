<div
	id="products"
	class=""
>

	@foreach ($wishlistItems as $item)
		<div
			class="product"
			wire:key="wishlist-item-{{ $item->id }}"
		>

			<div class="img-box relative">
				<a href="{{ $item->product->url() }}">
					<img
						src="{{ $item->product->image(color: $item->color_id, position: $item->position_id) }}"
						alt=""
						loading="lazy"
					>
				</a>

				<div
					class="absolute right-0 top-0 aspect-square cursor-pointer p-2 text-gray-500 transition-colors hover:text-red-500"
					wire:click="deleteItem({{ $item->id }})"
					wire:confirm="Sei sicuro di voler rimuovere questo prodotto dalla tua wishlist?"
				>
					<x-icon-delete class="w-4" />
				</div>
			</div>

			<h4 class="name mt-2">{{ $item->product->name }}</h4>

			<p class="shop">
				by <a
					class="hover:text-primary transition-colors"
					href="{{ $item->product->url() }}"
				>{{ $item->product->seller->name }}</a>
			</p>

		</div>
	@endforeach

</div>
