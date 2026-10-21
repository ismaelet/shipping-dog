<section
	class="min-w-0 md:row-start-2"
	x-data="{
    open: @js((bool) ($search || $selectedTypeId || $selectedGenderId))
}"
>
	{{-- Mobile toggle --}}
	<button
		type="button"
		class="border-surface-500 bg-surface flex w-full cursor-pointer items-center justify-between rounded-xl border px-4 py-3 text-left font-bold md:hidden"
		@click="open = !open"
		:aria-expanded="open.toString()"
		aria-controls="shop-filter-content"
	>
		Filtra la ricerca

		<x-icon-arrow-simple-down
			class="size-4 transition-transform duration-300"
			x-bind:class="{ 'rotate-180': open }"
		/>
	</button>

	{{-- Filters --}}
	<div
		id="shop-filter-content"
		x-show="open"
		x-cloak
		class="border-surface-500 bg-surface md:mt-0! md:block! md:rounded-none! md:border-0! md:bg-transparent! md:p-0! mt-2 rounded-xl border p-4"
	>
		{{-- Search --}}
		<form
			wire:submit="applySearch"
			class="mb-8"
		>
			<div class="bg-surface flex items-center rounded-full px-4 shadow-lg transition-shadow focus-within:shadow-sm">
				<input
					type="search"
					wire:model="search"
					placeholder="Cerca un prodotto"
					maxlength="30"
					autocomplete="off"
					class="text-neutral-heading min-w-0 flex-1 border-0 bg-transparent py-3 text-sm outline-none focus:ring-0"
				>

				<button
					type="submit"
					class="text-neutral-heading hover:text-primary-400 grid size-8 shrink-0 cursor-pointer place-items-center transition-colors"
					aria-label="Cerca"
				>
					<x-icon-search class="size-4" />
				</button>
			</div>
		</form>

		{{-- Categories --}}
		<h3 class="text-neutral-heading mb-3 font-medium">
			Modello
		</h3>

		<div class="space-y-1">
			<x-shop.category-selector
				title="Donna"
				:types="$womenTypes"
				:gender-id="2"
				:selected-type-id="$selectedTypeId"
				:selected-gender-id="$selectedGenderId"
			/>

			<x-shop.category-selector
				title="Uomo"
				:types="$menTypes"
				:gender-id="1"
				:selected-type-id="$selectedTypeId"
				:selected-gender-id="$selectedGenderId"
			/>

			<x-shop.category-selector
				title="Accessori"
				:types="$accessoryTypes"
				:selected-type-id="$selectedTypeId"
				:selected-gender-id="$selectedGenderId"
			/>
		</div>

		{{-- Clear filters --}}
		@if ($selectedTypeId || $selectedGenderId || $search)
			<button
				type="button"
				wire:click="removeFilters"
				class="text-neutral-heading hover:text-danger-400 mt-5 flex cursor-pointer items-center gap-2 text-sm font-semibold transition-colors"
			>
				<span aria-hidden="true">×</span>
				Rimuovi i filtri
			</button>
		@endif
	</div>
</section>
