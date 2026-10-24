@php
    $tabs = [
        'all' => ['label' => 'Tutti', 'count' => $designCount + $sellerCount],
        'designs' => ['label' => 'Design', 'count' => $designCount],
        'sellers' => ['label' => 'Negozi', 'count' => $sellerCount],
    ];
    $sorts = ['relevance' => 'Più rilevanti', 'popular' => 'Più popolari', 'recent' => 'Più recenti'];
    $featuredSeller =
        $type === 'all' && $sellers?->first() && mb_strtolower($sellers->first()->name) === mb_strtolower($search)
            ? $sellers->first()
            : null;
    $otherSellers = $featuredSeller ? $sellers->skip(1) : $sellers;
    $noResults = $isValid && $designCount + $sellerCount === 0;
@endphp

@pushOnce('styles')
    <style></style>
@endpushOnce

<x-layouts.main :$title :$location>
    <div class="full-width search-page content-start pb-20">
        <section class="full-width bg-surface-500 relative py-20 md:py-24">
            <img
                src="{{ Vite::image('search-banner.webp') }}"
                alt="Search Banner"
                class="fill-width absolute inset-0 h-full w-full object-cover"
            />
            <x-search.form :value="$search" class="relative" />
        </section>

        <div class="full-width pt-8">
            @if (! $isValid)
                <div class="mx-auto max-w-xl py-10 text-center">
                    <h1 class="text-neutral-heading text-xl font-semibold">
                        {{ $search === '' ? 'Cosa stai cercando?' : 'Ricerca troppo breve' }}
                    </h1>
                    <p class="mt-2">
                        {{ $search === '' ? 'Cerca un design, un negozio o un tag.' : "Inserisci almeno {$minLength} caratteri per avviare la ricerca." }}
                    </p>
                </div>
            @elseif ($noResults)
                <div class="mx-auto max-w-xl py-10 text-center">
                    <h1 class="text-neutral-heading text-xl font-semibold">Nessun risultato per "{{ $search }}"</h1>
                    <p class="mt-2">Prova a utilizzare parole più semplici oppure cerca un altro design o negozio.</p>
                </div>
            @else
                <div class="breakout">
                    {{-- Results summary --}}
                    <x-heading variant="article" class="text-neutral-heading text-xl font-semibold md:text-2xl">
                        {{ $designCount }} design e {{ $sellerCount }} {{ $sellerCount === 1 ? 'negozio' : 'negozi' }} per
                        "{{ $search }}"
                    </x-heading>

                    {{-- Tabs for filtering results --}}
                    <div class="mt-5 flex flex-wrap items-center justify-between gap-x-6 gap-y-3">
                        <nav aria-label="Categorie di risultati">
                            <ul class="flex flex-wrap gap-2">
                                @foreach ($tabs as $key => $tab)
                                    <li>
                                        <a
                                            href="{{ route('search', array_filter(['search' => $search, 'type' => $key === 'all' ? null : $key, 'sort' => $sort === 'relevance' ? null : $sort])) }}"
                                            @class([
                                                'focus-visible:outline-primary-400 inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-sm font-medium transition-colors focus-visible:outline-2 focus-visible:outline-offset-2',
                                                'bg-primary-400 text-white' => $type === $key,
                                                'bg-surface-400 text-neutral-heading hover:bg-surface-500' => $type !== $key,
                                            ])
                                            @if ($type === $key) aria-current="page" @endif
                                        >
                                            {{ $tab['label'] }}
                                            <span class="text-sm opacity-80">{{ $tab['count'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </nav>

                        {{-- Sorting options for designs --}}
                        @if ($type !== 'sellers' && $designCount > 1)
                            <nav aria-label="Ordinamento design">
                                <ul class="flex flex-wrap gap-x-4 gap-y-1 text-sm">
                                    @foreach ($sorts as $key => $label)
                                        <li>
                                            <a
                                                href="{{ route('search', array_filter(['search' => $search, 'type' => $type === 'all' ? null : $type, 'sort' => $key === 'relevance' ? null : $key])) }}"
                                                @class([
                                                    'focus-visible:outline-primary-400 transition-colors focus-visible:outline-2',
                                                    'text-neutral-heading font-semibold underline underline-offset-4' => $sort === $key,
                                                    'hover:text-neutral-heading' => $sort !== $key,
                                                ])
                                                @if ($sort === $key) aria-current="true" @endif
                                            >{{ $label }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </nav>
                        @endif
                    </div>

                    @if ($featuredSeller)
                        <section class="mt-8" aria-label="Negozio corrispondente">
                            <x-search.seller-card :seller="$featuredSeller" featured />
                        </section>
                    @endif
                </div>

                @if ($type !== 'sellers')
                    <section class="results-group full-width mt-8">
                        <div class="breakout">
                            <h2 class="text-neutral-heading text-lg font-semibold">
                                Design <span class="text-neutral-text text-base font-normal">({{ $designCount }})</span>
                            </h2>

                            @if ($designs->isEmpty())
                                <p class="mt-4">Nessun design corrisponde a "{{ $search }}".</p>
                            @else
                                <div class="mt-4 grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3 md:gap-x-6 lg:grid-cols-4 2xl:grid-cols-5">
                                    @foreach ($designs as $design)
                                        <x-search.design-card :$design />
                                    @endforeach
                                </div>

                                <div class="mt-10">{{ $designs->onEachSide(1)->links('pagination.default') }}</div>
                            @endif
                        </div>
                    </section>
                @endif

                @if ($type !== 'designs' && $otherSellers->isNotEmpty() || $type === 'sellers')
                    <section class="results-group full-width mt-12">
                        <div class="breakout">
                            <h2 class="text-neutral-heading text-lg font-semibold">
                                Negozi <span class="text-neutral-text text-base font-normal">({{ $sellerCount }})</span>
                            </h2>

                            @if ($sellers->isEmpty())
                                <p class="mt-4">Nessun negozio corrisponde a "{{ $search }}".</p>
                            @else
                                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 lg:grid-cols-3 xl:grid-cols-4">
                                    @foreach ($type === 'sellers' ? $sellers : $otherSellers as $seller)
                                        <x-search.seller-card :$seller />
                                    @endforeach
                                </div>

                                @if ($type === 'sellers')
                                    <div class="mt-10">{{ $sellers->onEachSide(1)->links('pagination.default') }}</div>
                                @elseif ($sellerCount > $sellers->count())
                                    <a
                                        href="{{ route('search', ['search' => $search, 'type' => 'sellers']) }}"
                                        class="text-primary-400 hover:text-primary-500 mt-6 inline-block font-semibold"
                                    >Vedi tutti i {{ $sellerCount }} negozi</a>
                                @endif
                            @endif
                        </div>
                    </section>
                @endif
            @endif
        </div>
    </div>
</x-layouts.main>
