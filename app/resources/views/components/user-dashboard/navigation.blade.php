@php
    $links = [
        [
            'title' => 'Attività recenti',
            'dashboard_location' => 'recap',
            'url' => route('user-dashboard'),
            'icon' => 'user',
        ],
        [
            'title' => 'Wishlist',
            'dashboard_location' => 'wishlist',
            'url' => route('user-dashboard.wishlist'),
            'icon' => 'heart',
        ],
        [
            'title' => 'Lista ordini',
            'dashboard_location' => 'orders',
            'url' => route('user-dashboard.orders'),
            'icon' => 'orders',
        ],
        [
            'title' => 'Il mio negozio',
            'dashboard_location' => null,
            'url' => route('seller.show', ['seller' => auth()->user()->id]),
            'icon' => 'shop',
            'condition' => auth()->user()->isSeller(),
        ],
        [
            'title' => 'I miei dati',
            'dashboard_location' => 'settings',
            'url' => route('user-dashboard.settings'),
            'icon' => 'settings',
        ],
        [
            'title' => 'Logout',
            'dashboard_location' => null,
            'url' => route('logout'),
            'icon' => 'logout',
        ],
    ];
@endphp

@pushOnce('styles')
    <style>
        .user-dashboard-navigation {
            position: sticky;
            top: calc(var(--header-height) + 1rem);
        }

        @media (max-width: 768px) {
            .user-dashboard-navigation {
                grid-template-rows: max-content 0fr;
                transition: grid-template-rows 0.3s;

                [data-open='true'] & {
                    grid-template-rows: max-content 1fr;
                }
            }
        }
    </style>
@endpushOnce

<nav x-data="{ open: false }" :data-open="open ? 'true' : 'false'">
    <div class="user-dashboard-navigation surface border-border-400 bg-surface-400 grid overflow-clip rounded-xl border">
        {{-- Title --}}
        <label class="bg-surface-500 md:hidden" @click="open = ! open">
            <h3 class="text-heading-400 flex items-center gap-4 px-4 py-4 text-xl">
                <span class="w-5">
                    <x-icon-list />
                </span>
                Profilo
            </h3>
        </label>

        {{-- Navigation link --}}
        <div class="overflow-hidden">
            <ul class="grid grid-cols-1 gap-2 p-2">
                @foreach ($links as $link)
                    @if (! isset($link['condition']) || $link['condition'])
                        <x-user-dashboard.link :$link />
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</nav>
