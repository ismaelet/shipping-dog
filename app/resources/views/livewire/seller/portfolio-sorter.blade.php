<div
    x-data="portfolioSorter"
    x-init="setup($el, $wire)"
    class="border-border-400 bg-surface-400 rounded-3xl border p-6 sm:p-8"
>
    <div class="border-border-400 flex flex-wrap items-end justify-between gap-4 border-b pb-6">
        <div>
            <x-eyebrow>Le tue creazioni</x-eyebrow>
            <x-heading variant="article">Portfolio</x-heading>
            <p class="mt-3 text-sm">{{ $designs->count() }} grafiche pubblicate</p>
        </div>
        <x-button href="{{ route('seller-dashboard.portfolio.add') }}" size="sm">
            Carica una grafica
            <x-icon-upload />
        </x-button>
    </div>

    @if ($designs->isNotEmpty() || $customProducts->isNotEmpty())
        <div class="mt-5 grid gap-6">
            <div class="bg-background-400 flex items-center gap-3 rounded-xl px-4 py-3">
                <x-icon-drag-handler class="text-heading-400 size-4 shrink-0" />
                <p>
                    <span class="text-heading-400 font-semibold">Riordina il portfolio:</span>
                    trascina le grafiche nella posizione desiderata.
                </p>
            </div>

            @if ($designs->count())
                <div
                    id="gallery"
                    data-type="design"
                    wire:ignore
                    class="js-sortable grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
                >
                    @foreach ($designs as $design)
                        <article
                            data-id="{{ $design->id }}"
                            class="border-border-400 bg-background-400 overflow-hidden rounded-2xl border"
                        >
                            <input type="hidden" name="id[]" value="{{ $design->id }}" />
                            <input type="hidden" class="js-order" name="pos[]" value="{{ $design->sort }}" />
                            <a class="text-heading-400 block" href="{{ route('designs.edit', $design) }}">
                                <div class="relative">
                                    <img
                                        class="portfolio-card-image block w-full object-cover"
                                        src="{{ $design->image() }}"
                                        alt="{{ $design->name }}"
                                        loading="lazy"
                                    />
                                    <div class="js-drag-handle bg-background-400/70 absolute top-2 right-2 grid size-10 cursor-grab place-items-center rounded-full backdrop-blur-md active:cursor-grabbing">
                                        <x-icon-drag-handler class="text-heading-400 size-4" />
                                    </div>
                                </div>
                                <div class="p-3">
                                    <h2 class="truncate font-semibold">{{ $design->name }}</h2>
                                    <p class="text-text-400 mt-1 text-xs">{{ $design->products_count }} Prodotti</p>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            @else
                <p class="text-center">Ancora non hai caricato nessuna grafica.</p>
            @endif

            @if ($customProducts->count())
                <div data-type="product" wire:ignore class="js-sortable grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($customProducts as $product)
                        <article
                            data-id="{{ $product->id }}"
                            class="border-border-400 bg-background-400 relative overflow-hidden rounded-2xl border"
                        >
                            <a
                                class="text-heading-400 block"
                                href="{{ route('seller-dashboard.portfolio.products.show', $product) }}"
                            >
                                <img
                                    class="portfolio-card-image block w-full object-cover"
                                    src="{{ $product->image() }}"
                                    alt="{{ $product->name }}"
                                    loading="lazy"
                                />

                                <div class="p-3">
                                    <h2 class="text-sm font-semibold">{{ $product->name }}</h2>
                                    <p class="text-text-400 mt-1 text-xs">{{ $product->type->name }}</p>

                                    @if (! $product->status)
                                        <p class="text-danger-400 mt-1 text-xs">Non pubblicato</p>
                                    @endif
                                </div>
                            </a>

                            <button
                                type="button"
                                class="js-drag-handle bg-background-400/70 absolute top-2 right-2 grid size-10 cursor-grab place-items-center rounded-full backdrop-blur-md active:cursor-grabbing"
                                aria-label="Trascina per riordinare"
                            >
                                <x-icon-drag-handler class="text-heading-400 size-4" />
                            </button>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    @else
        <p class="py-8 text-center">Ancora non hai caricato nessuna grafica. Clicca qui sopra per cominciare.</p>
    @endif

    <div class="mt-4 text-sm" aria-live="polite">
        <span x-show="saving" x-cloak class="text-text-400"> Salvataggio in corso... </span>

        <span x-show="saved && ! saving" x-cloak class="text-success-400"> Ordine salvato </span>

        <span x-show="error && ! saving" x-cloak class="text-danger-400"> Errore durante il salvataggio. </span>
    </div>
</div>
