<div
	id="product-list"
	class="grid min-w-0 grid-cols-1 gap-x-12 gap-y-8 md:grid-cols-[16rem_minmax(0,1fr)]"
>
	@if ($seller)
		<div class="md:col-span-2">
			<x-heading.component>
				Shop
				<span class="text-neutral-300">
					({{ $products->total() }} risultati)
				</span>
			</x-heading.component>
		</div>
	@endif

	@include('livewire.shop.product-list._sort')

	@include('livewire.shop.product-list._filters')

	@include('livewire.shop.product-list._results')
</div>
