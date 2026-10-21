<div class="xs:grid-cols-2 grid grid-cols-1 gap-x-5 gap-y-8 lg:grid-cols-3 xl:grid-cols-4">
	@foreach ($wishlistItems as $item)
		@if ($item->product)
			@php
				$item->product->color_id = $item->color_id;
				$item->product->position_id = $item->position_id;
			@endphp

			<div wire:key="wishlist-item-{{ $item->id }}">
				<x-product-card :product="$item->product" />
			</div>
		@endif
	@endforeach
</div>
