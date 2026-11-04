@props(['seller'])

@php
    $navigation = [
        [
            'title' => 'Impostazioni',
            'subtitle' => 'Generali',
            'route' => 'seller-dashboard',
            'activeRoutes' => ['seller-dashboard'],
            'icon' => 'settings',
        ],
        [
            'title' => 'Grafiche',
            'subtitle' => 'Prodotti e design',
            'route' => 'seller-dashboard.portfolio',
            'activeRoutes' => ['seller-dashboard.portfolio*'],
            'icon' => 'shirt',
        ],
        [
            'title' => 'Vendite',
            'subtitle' => 'Ordini e guadagni',
            'route' => 'seller-dashboard.sales',
            'activeRoutes' => ['seller-dashboard.sales'],
            'icon' => 'shop',
        ],
        [
            'title' => 'Prezzi',
            'subtitle' => 'Listino e margini',
            'route' => 'seller-dashboard.prices',
            'activeRoutes' => ['seller-dashboard.prices'],
            'icon' => 'price',
        ],
    ];
@endphp

@pushOnce('styles')
    <style>
        .seller-dashboard-navigation {
            top: calc(var(--header-height) + 1rem);
        }

        @media (max-width: 1023px) {
            .seller-dashboard-navigation {
                display: grid;
                grid-template-rows: max-content 0fr;
                transition: grid-template-rows 0.3s;
            }

            .seller-dashboard-categories {
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.4s;
            }

            .seller-dashboard-navigation:has(.seller-dashboard-mobile-toggle:checked) {
                grid-template-rows: max-content 1fr;
            }

            .seller-dashboard-navigation:has(.seller-dashboard-mobile-toggle:checked) .seller-dashboard-categories {
                max-height: 32rem;
            }
        }
    </style>
@endPushOnce

<aside class="seller-dashboard-navigation lg:sticky lg:mt-12 lg:self-start">
    <label class="text-heading-400 font-heading flex cursor-pointer items-center justify-center gap-3 py-5 text-lg lg:hidden">
        <input type="checkbox" class="seller-dashboard-mobile-toggle sr-only" />
        <x-icon-list class="size-5" />
        <span>Menu gestione</span>
    </label>

    <div class="seller-dashboard-categories">
        <nav
            aria-label="Menu gestione negozio"
            class="border-border-400 bg-surface-400 grid gap-2 rounded-2xl border p-2 shadow-sm"
        >
            <div class="px-4 pt-2 pb-1">
                <x-eyebrow>Dashboard negozio</x-eyebrow>
                <x-heading variant="article-small">{{ $seller->name }}</x-heading>
            </div>

            @foreach ($navigation as $item)
                @php
                    $active = request()->routeIs(...$item['activeRoutes']);
                @endphp

                <a
                    href="{{ route($item['route']) }}"
                    @class([
                        'flex items-center gap-3 rounded-xl px-4 py-3 transition-colors',
                        'bg-primary-400 text-white' => $active,
                        'text-heading-400 hover:bg-surface-500' => ! $active,
                    ])
                    @if ($active) aria-current="page" @endif
                >
                    <x-dynamic-component
                        :component="'icon-'.$item['icon']"
                        @class([
                            'size-5 shrink-0',
                            'fill-current' => $item['icon'] === 'discount',
                        ])
                    />
                    <span class="min-w-0">
                        <span class="block text-sm leading-5 font-bold">{{ $item['title'] }}</span>
                        <span
                            @class([
                                'block text-xs leading-4',
                                'text-white/80' => $active,
                                'text-text-400' => ! $active,
                            ])
                        >{{ $item['subtitle'] }}</span>
                    </span>
                </a>
            @endforeach

            <a
                href="{{ route('seller.show', $seller) }}"
                class="text-text-400 hover:text-heading-400 flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-colors"
            >
                <x-icon-keyboard-arrow-right class="mr-2 size-3 shrink-0 rotate-180" />
                <span>Torna al profilo</span>
            </a>
        </nav>
    </div>
</aside>
