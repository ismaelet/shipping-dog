@props(['image', 'productId'])

<div
	class="image-panel"
	wire:key="product-preview-{{ $productId }}"
>
	<div class="image mar-h-auto stack-content relative grid aspect-square w-full overflow-hidden rounded-[3px]">
		<img
			class="w-full object-cover"
			src="{{ $image }}"
			alt=""
			data-preview-image
		>
	</div>
</div>
