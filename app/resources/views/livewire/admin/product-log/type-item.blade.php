<div class="flex items-center gap-2">
	{{-- Row --}}
	@if ($category === 'design')
		<a
			href="{{ $product->fullUrl(color_id: $color->id) }}"
			class="js-to-copy"
		>
			{{ $quantity }}
			x
			{{ $type->name }}
			{{ $color->name . ' ' . $size->name }}
		</a>
	@else
		<p class="js-to-copy">
			{{ $quantity }}
			x
			{{ $color->name . ' ' . $size->name }}
		</p>
	@endif

	{{-- Added as print --}}
	<div
		class="data-[active=true]:text-secondary-400 cursor-pointer opacity-30 transition-all data-[active=true]:opacity-100"
		data-active="{{ $isPrintedCount ? 'true' : 'false' }}"
		wire:click="setIsPrinted"
	>
		<x-icon-print class="h-4 w-4" />
	</div>

	@if ($isPrintedCount && $isNotPrintedCount)
		<p class="text-sm">
			({{ $isPrintedCount }}/{{ $isPrintedCount + $isNotPrintedCount }})
		</p>
	@endif

	{{-- Added to cart --}}
	<div
		class="data-[active=true]:text-success-400 cursor-pointer opacity-30 transition-all data-[active=true]:opacity-100"
		data-active="{{ $isInCartCount ? 'true' : 'false' }}"
		wire:click="setInCart"
	>
		<x-icon-cart class="h-4 w-4" />
	</div>

	@if ($isInCartCount && $isNotInCartCount)
		<p class="text-sm">
			({{ $isInCartCount }}/{{ $isInCartCount + $isNotInCartCount }})
		</p>
	@endif
</div>
