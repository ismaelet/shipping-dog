<x-user-dashboard :$title>
    @php
        $stats = [
            [
                'label' => 'Ordini effettuati',
                'value' => $orderCount,
                'icon' => 'orders',
                'iconClass' => 'bg-primary-400/10 text-primary-400',
            ],
            [
                'label' => 'Prodotti nella wishlist',
                'value' => $wishlistCount,
                'icon' => 'heart',
                'iconClass' => 'bg-detail-2-400/10 text-detail-2-400',
            ],
            [
                'label' => 'Shop seguiti',
                'value' => $followedShopCount,
                'icon' => 'shop',
                'iconClass' => 'bg-detail-1-400/10 text-detail-1-400',
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 gap-4">
        <section aria-label="Statistiche account" class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            @foreach ($stats as $stat)
                <article class="border-border-400 bg-surface-400 rounded-xl border p-5">
                    <div class="flex items-center justify-between">
                        <span class="{{ $stat['iconClass'] }} grid size-10 place-items-center rounded-full p-2.5">
                            <x-dynamic-component :component="'icon-'.$stat['icon']" />
                        </span>
                        <span class="font-heading text-heading-400 text-2xl leading-none font-bold">
                            {{ str_pad((string) $stat['value'], 2, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                    <p class="text-text-400 mt-4 text-sm">{{ $stat['label'] }}</p>
                </article>
            @endforeach
        </section>

        <section class="card border-border-400 bg-surface-400 rounded-2xl border p-5 sm:p-8">
            <x-user-dashboard.panel-header
                title="La tua attività"
                subtitle="Bentornato"
                text="Tutto quello che è successo nel tuo mondo ShippingDOG, in un solo posto."
            />

            <div class="mt-6 grid grid-cols-1 gap-3">
                @forelse ($feedEntries as $feedEntry)
                    <x-user-dashboard.feed-entry :entry="$feedEntry" />
                @empty
                    <p class="text-text-400 py-10 text-center text-sm">Non ci sono ancora attività da mostrare.</p>
                @endforelse
            </div>
        </section>
    </div>
</x-user-dashboard>
