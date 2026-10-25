<x-layouts.main :title="$seller->name" location-tag="shop">
    <div class="full-width">
        {{-- Seller profile --}}
        <section class="breakout pt-4">
            <div class="relative">
                {{-- Cover --}}
                <img
                    src="{{ $seller->cover() }}"
                    alt=""
                    class="bg-primary-400 h-64 w-full rounded-3xl object-cover sm:h-80 lg:h-120"
                />

                {{-- Edit profile --}}
                @if ($owner)
                    <a
                        href="{{ route('seller-dashboard') }}"
                        class="hover:bg-surface-400/50 absolute top-4 right-4 rounded-full border border-white/20 bg-black/50 px-5 py-2 text-sm font-semibold text-white backdrop-blur-md transition-colors"
                    >
                        Modifica negozio
                    </a>
                @endif
            </div>

            {{-- Profile information --}}
            <div class="relative text-center">
                <div class="grid w-full sm:grid-cols-[1fr_auto_1fr]">
                    <div class=""></div>

                    <div class="">
                        {{-- Avatar --}}
                        <img
                            src="{{ $seller->icon() }}"
                            alt="Logo di {{ $seller->name }}"
                            class="border-background-400 bg-surface-500 relative mx-auto -mt-12 size-24 rounded-full border-8 object-cover shadow-lg sm:-mt-16 sm:size-32"
                        />

                        <div class="grid grid-cols-1 gap-4">
                            {{-- Statistics --}}
                            <div class="text-text-400 mt-4 flex flex-wrap justify-center gap-x-3 gap-y-1 text-sm">
                                <span>{{ $productCount }} Prodotti</span>
                                <span aria-hidden="true">·</span>
                                <span>{{ $likeN }} Like</span>
                                <span aria-hidden="true">·</span>
                                <span>{{ $follN }} Follower</span>
                            </div>

                            {{-- Seller name --}}
                            <h1 class="text-heading-400 text-trim-start text-3xl font-bold tracking-tight sm:text-5xl">
                                {{ $seller->name }}
                            </h1>

                            {{-- Social links --}}
                            @if ($user?->contacts)
                                <div class="flex flex-wrap justify-center gap-5">
                                    @foreach ($contacts as $contact)
                                        @if ($url = $user->$contact())
                                            <a
                                                href="{{ $url }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                aria-label="{{ ucfirst($contact) }}"
                                                class="text-text-400 hover:text-primary-400 transition-colors"
                                            >
                                                <x-dynamic-component :component="'icon-'.$contact" class="size-5" />
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                            {{-- Description --}}
                            @if (filled($seller->description))
                                <p class="text-text-400 mx-auto max-w-2xl leading-relaxed">
                                    {{ $seller->description }}
                                </p>
                            @endif

                            {{-- Activation request --}}
                            @if ($owner && ! $seller->isActive())
                                <div class="">
                                    <livewire:seller.activation-request :$seller />
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Follow button --}}
                    <div class="mt-4 sm:justify-self-end">
                        <livewire:follow-button :$seller />
                    </div>
                </div>
            </div>
        </section>

        {{-- Products --}}
        @if ($hasItems)
            <section aria-label="Prodotti del negozio" class="">
                <livewire:shop.product-list :$seller />
            </section>

            {{-- Collections --}}
            @if ($collN > 0 || $owner)
                <div class="full-width bg-surface-400">
                    <x-seller.collections-section :$seller :$owner :$collN :$collBlock />
                </div>
            @endif
        @else
            {{-- Empty shop --}}
            <section class="mx-auto max-w-3xl px-5 py-20 text-center">
                <div class="bg-surface-500/30 rounded-3xl border border-dashed border-neutral-300 px-6 py-16 dark:border-white/10">
                    <div class="bg-surface-500 mx-auto mb-6 grid size-16 place-items-center rounded-full">
                        <x-icon-add class="text-text-400 size-7" />
                    </div>

                    <h2 class="text-heading-400 text-2xl font-bold">Ancora nessun lavoro caricato</h2>

                    @if ($owner)
                        <p class="text-text-400 mx-auto mt-3 max-w-md">
                            Inizia a personalizzare il tuo negozio caricando la tua prima grafica.
                        </p>

                        <div class="mt-8">
                            <x-button :href="route('seller-dashboard.portfolio.add')"> Carica un lavoro </x-button>
                        </div>
                    @endif
                </div>
            </section>
        @endif
    </div>
</x-layouts.main>
