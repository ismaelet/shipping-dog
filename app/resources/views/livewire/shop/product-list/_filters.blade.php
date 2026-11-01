@pushOnce('styles')
    <style>
        .product-list-filters-wrapper {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            align-content: start;
            top: calc(var(--header-height) + 1rem);
            transition: grid-template-rows 0.2s ease-in-out;

            @media (width < 48rem) {
                grid-template-rows: auto 0fr;

                &:has(.product-list-filters[data-open='true']) {
                    grid-template-rows: auto 1fr;
                }
            }
        }

        .product-list-filters {
            grid-template-rows: 0fr;
            transition: grid-template-rows 0.2s ease-in-out;
        }

        .product-list-filters-toggle[aria-expanded='true'] + .product-list-filters {
            grid-template-rows: 1fr;
        }

        @media (min-width: 48rem) {
            .product-list-filters {
                grid-template-rows: minmax(0, 1fr);
            }
        }
    </style>
@endpushOnce

<section
    class="min-w-0"
    x-data="{
    open: @js((bool) ($search || $selectedTypeId || $selectedGenderId))
}"
>
    <div class="product-list-filters-wrapper sticky">
        {{-- Mobile toggle --}}
        <button
            type="button"
            class="product-list-filters-toggle text-heading-400 border-border-400 bg-surface-400 flex w-full cursor-pointer items-center justify-between rounded-xl border px-4 py-3 text-left font-bold md:hidden"
            @click="open = ! open"
            :aria-expanded="open.toString()"
            aria-controls="product-list-filters"
        >
            Filtra la ricerca

            <x-icon-arrow-simple-down
                class="size-4 transition-transform duration-300"
                x-bind:class="{ 'rotate-180': open }"
            />
        </button>

        {{-- Filters --}}
        <div
            id="product-list-filters"
            class="product-list-filters mt-2 grid grid-cols-1 overflow-hidden md:mt-0 md:overflow-visible"
            x-bind:data-open="open"
        >
            <div class="">
                <h3 class="font-heading text-heading-400 mb-4 hidden text-base font-semibold md:block">Categoria</h3>

                <div class="card overflow-hidden rounded-2xl">
                    <button
                        type="button"
                        wire:click="removeFilters"
                        @class([
                            'flex w-full cursor-pointer items-center justify-between px-4 py-3.5 text-left text-sm font-bold transition-colors',
                            'bg-orange-500 text-white' => ! $search && ! $selectedTypeId && ! $selectedGenderId,
                            'text-heading-400 hover:bg-surface-500' => $search || $selectedTypeId || $selectedGenderId,
                        ])
                        aria-pressed="{{ !$search && !$selectedTypeId && !$selectedGenderId ? 'true' : 'false' }}"
                    >
                        <span>Tutti i prodotti</span>
                    </button>

                    <x-shop.category-selector
                        title="Donna"
                        :types="$womenTypes"
                        :gender-id="2"
                        :selected-type-id="$selectedTypeId"
                        :selected-gender-id="$selectedGenderId"
                        :default-open="$selectedGenderId === 2"
                    />

                    <x-shop.category-selector
                        title="Uomo"
                        :types="$menTypes"
                        :gender-id="1"
                        :selected-type-id="$selectedTypeId"
                        :selected-gender-id="$selectedGenderId"
                        :default-open="$selectedGenderId === 1"
                    />

                    <x-shop.category-selector
                        title="Unisex"
                        :types="$unisexTypes"
                        :gender-id="3"
                        :selected-type-id="$selectedTypeId"
                        :selected-gender-id="$selectedGenderId"
                        :default-open="$selectedGenderId === 3"
                    />

                    <x-shop.category-selector
                        title="Accessori"
                        :types="$accessoryTypes"
                        :selected-type-id="$selectedTypeId"
                        :selected-gender-id="$selectedGenderId"
                    />
                </div>

                @if ($selectedTypeId || $selectedGenderId || $search)
                    <button
                        type="button"
                        wire:click="removeFilters"
                        class="hover:text-danger-400 mt-4 flex cursor-pointer items-center gap-2 text-sm font-semibold transition-colors"
                    >
                        <span aria-hidden="true"><x-icon-close class="size-4" /></span>
                        Rimuovi i filtri
                    </button>
                @endif

                <div class="mt-8 hidden rounded-2xl bg-orange-500/15 p-5 md:block">
                    <img src="{{ Vite::image('shop/independent-design.svg') }}" alt="" class="size-6" />

                    <h3 class="font-heading text-heading-400 mt-4 text-base font-semibold">Design indipendenti</h3>

                    <p class="dark:text-primary-200 text-heading-400/70 mt-2 text-sm leading-6">
                        Ogni acquisto sostiene direttamente un artista.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
