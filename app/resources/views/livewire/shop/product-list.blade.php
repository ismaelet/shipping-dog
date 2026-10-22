<div
	id="product-list"
	class="py-16 md:py-20"
>
	<header class="mb-10 flex flex-col gap-6 border-b border-white/10 pb-8 md:flex-row md:items-end md:justify-between">
		<div>
			<p class="text-xs font-bold uppercase tracking-[0.2em] text-orange-500">
				La collezione
			</p>

			<h2 class="font-heading text-neutral-heading mt-2 text-3xl font-semibold leading-tight tracking-tight sm:text-4xl">
				{{ $seller ? $seller->name : 'Tutti i prodotti' }}
			</h2>

			<p class="mt-2 text-sm">
				{{ $totalProducts }} articoli
			</p>
		</div>

		<form
			wire:submit="applySearch"
			class="flex w-full items-center gap-3 rounded-full border border-white/10 bg-slate-800 px-5 py-3 transition-colors focus-within:border-white/20 md:max-w-[288px]"
		>
			<button
				type="submit"
				class="hover:text-neutral-heading grid size-5 shrink-0 cursor-pointer place-items-center transition-colors"
				aria-label="Cerca"
			>
				<x-icon-search class="size-4" />
			</button>

			<input
				type="search"
				wire:model="search"
				placeholder="Cerca nella collezione..."
				maxlength="30"
				autocomplete="off"
				class="text-neutral-heading placeholder: min-w-0 flex-1 border-0 bg-transparent p-0 text-sm outline-none focus:ring-0"
			>
		</form>
	</header>

	<div class="grid min-w-0 grid-cols-1 gap-8 md:grid-cols-[15rem_minmax(0,1fr)]">
		@include('livewire.shop.product-list._filters')

		@include('livewire.shop.product-list._results')
	</div>
</div>
