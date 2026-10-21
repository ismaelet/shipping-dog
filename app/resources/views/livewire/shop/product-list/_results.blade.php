<section
	id="shop-items"
	class="relative min-w-0 pb-12 md:col-start-2 md:row-start-2"
>
	@if ($products->count())
		<div
			id="items"
			class="items_panel"
		>
			<div class="grid grid-cols-2 gap-x-4 gap-y-8 lg:grid-cols-3 2xl:grid-cols-4 min-[1800px]:grid-cols-5">
				@foreach ($products as $product)
					<x-product-card
						:$product
						:key="'product-' . $product->id"
					/>
				@endforeach
			</div>

			{{ $products->onEachSide(1)->links('pagination.livewire') }}
		</div>
	@else
		<div class="grid h-full grid-cols-1 content-start justify-items-center py-32 text-center">
			<x-icon-warning class="h-24 w-24" />

			<p class="text-600">Nessun articolo trovato</p>

			@if ($selectedType || $selectedGender || $search)
				<livewire:shop.clear-filters />
			@endif
		</div>
	@endif

	<div
		class="bg-background-400/90 absolute inset-0 flex items-start justify-center"
		wire:loading.flex
	>
		<x-icon-loading class="mt-[30vh] w-20" />
	</div>
</section>
