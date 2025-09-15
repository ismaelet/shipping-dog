<div class="flex items-center gap-2">
	{{-- Row --}}
	<p class="js-to-copy">
		{{ $quantity }}
		x
		@if ($category === 'design')
			{{ $type->name }}
		@endif
		{{ $color->name . ' ' . $size->name }}
	</p>

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
