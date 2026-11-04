@php
    $currentLocation = explode('user-dashboard.', request()->route()->getName())[1] ?? 'recap';
    $dashboardLocation = $link['dashboard_location'];
    $checked = $currentLocation == $dashboardLocation;
@endphp

<li class="list-none">
    <a
        href="{{ $link['url'] }}"
        @if ($dashboardLocation) wire:navigate data-preserve-scroll @endif
        @class([
            'block rounded-lg px-4 py-3 transition-colors',
            'bg-primary-400 text-white' => $checked,
            'text-text-400 hover:bg-surface-500 hover:text-heading-400' => ! $checked,
        ])
    >
        <p class="font-heading flex items-center gap-4 text-sm lg:text-base">
            <x-dynamic-component class="h-5 w-5" :component="'icon-'.$link['icon']" />

            {{ $link['title'] }}
        </p>
    </a>
</li>
