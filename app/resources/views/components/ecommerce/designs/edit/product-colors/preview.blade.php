@props (['image', 'productId'])

<div
  class="aspect-square overflow-hidden rounded-lg"
  wire:key="product-preview-{{ $productId }}"
>
  <img
    class="h-full w-full object-cover"
    src="{{ $image }}"
    alt="Anteprima colori del prodotto"
  />
</div>
