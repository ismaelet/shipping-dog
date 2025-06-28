<form method="GET">

	@if ($seller)
		{{-- Title --}}
		<x-heading.component class="">
			Shop <span class="text-neutral-300">({{ $productCount }} risultati)</span>
		</x-heading.component>
	@endif

	<div id="product-list">
		<!-- Sort items -->
		<section id="shop-sort">

			<label>
				<input
					type="radio"
					class="sort-cbx hidden"
					name="sort"
					value="new"
					@checked($sort == 'new')
					wire:click="changeSort('new')"
				>
				<p class="sort_txt clickable">Nuovi</p>
			</label>

			<p class="sort_txt">&middot;</p>

			<label>
				<input
					type="radio"
					class="sort-cbx hidden"
					name="sort"
					value="popular"
					@checked($sort == 'popular')
					wire:click="changeSort('popular')"
				>
				<p class="sort_txt clickable">Popolari</p>
			</label>
		</section>

		<!-- Search bar -->
		<section id="shop-search">

			<p
				id="filter-text"
				onclick="$(this).toggleClass('open'); $(this).next().toggleClass('open');"
			>
				Filtra la ricerca
				<span class="search-ddown-ico icon"><?= embed_icon('arrow-simple-down') ?></span>
			</p>

			<div id="search-sec">
				<div
					id="search-mob-box"
					class="pad-b-3"
				>
					<div class="mar-b-2">

						<div
							id="search-inp-box"
							class="al-mid grid"
						>

							<input
								type="search"
								id="search-inp"
								class=""
								name="search"
								value="{{ $search }}"
								placeholder="Cerca un prodotto"
								maxlength="30"
								autocomplete="off"
							>

							<button
								type="submit"
								id="search-ico"
								class="clickable icon"
							><x-icon-search class="w-4" /></button>

						</div>

					</div>

					<div class="srch-box">
						<h3 class="srch-title">Modello</h3>

						{{-- Women's clothing --}}
						<livewire:shop.category-selector
							genderId="2"
							category="c"
							:$seller
							:$selectedGender
							:$selectedType
							:$selectedCategory
						/>

						{{-- Men's clothing --}}
						<livewire:shop.category-selector
							genderId="1"
							category="c"
							:$seller
							:$selectedGender
							:$selectedType
							:$selectedCategory
						/>

						{{-- Accessories --}}
						<livewire:shop.category-selector
							category="m"
							:$seller
							:$selectedGender
							:$selectedType
							:$selectedCategory
						/>

						<button
							type="submit"
							id="filter-type"
							class="hidden"
						></button>

						@if ($selectedType || $selectedGender || $search)
							<div class="mt-2 text-sm">
								<livewire:shop.clear-filters />
							</div>
						@endif
					</div>
				</div>
			</div>
		</section>

		<section
			id="shop-items"
			class="pad-b-3 relative"
		>

			@if ($products->count())
				<div
					id="items"
					class="items_panel"
				>

					<div class="grid5 grid4-1440w grid3-1024w grid2-480w col-gap1 row-gap2">

						@foreach ($products as $product)
							<livewire:shop.product-card
								:$product
								:key="'product-' . $product->id"
							/>
						@endforeach

					</div>

					<livewire:shop.pagination
						:$page
						:$productCount
						:$pageSize
					/>
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
				class="bg-background/90 absolute inset-0 flex items-start justify-center"
				wire:loading.flex
			>
				<x-icon-loading class="mt-[30vh] w-20" />
			</div>
		</section>
	</div>
</form>
