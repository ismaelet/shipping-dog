<section
	id="shop-items"
	class="relative min-w-0"
>
	<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
		<p class="text-sm">
			Mostra {{ $productCount }} risultati
		</p>

		@include('livewire.shop.product-list._sort')
	</div>

	@if ($products->count())
		<div class="grid grid-cols-2 gap-x-4 gap-y-8 sm:columns-[15rem] sm:gap-x-5 sm:gap-y-10">
			@foreach ($products as $product)
				<x-product-card
					:$product
					:key="'product-' . $product->id"
				/>
			@endforeach
		</div>

		<div class="mt-12">
			{{ $products->onEachSide(1)->links('pagination.livewire') }}
		</div>
	@else
		<div class="text-heading-400 grid h-full grid-cols-1 content-start justify-items-center py-32 text-center">
			<x-icon-warning class="h-24 w-24" />

			<p class="mt-4 text-xl font-semibold">Nessun articolo trovato</p>
		</div>
	@endif

	<div
		class="absolute inset-0 flex items-start justify-center bg-slate-900/90"
		wire:loading.flex
	>
		<x-icon-loading class="mt-[30vh] w-20" />
	</div>
</section>
