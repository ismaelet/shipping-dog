<div class="grid gap-x-5 gap-y-8 sm:columns-[10rem]">
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
