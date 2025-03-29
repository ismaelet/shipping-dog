@php
    $currentLocation = $locationTag ?? Request::route()->getName();
    $isActive = $link['location_tag'] == $currentLocation;
    $link['type'] = $link['type'] ?? 'link';
    $show = isset($link['auth']) ? $link['auth'] == Auth::check() : true;
    $username = Auth::user() ? Auth::user()->first_name : null;
    $linkLabel = populate_variables($link['label'], ['username' => $username]);
@endphp

@pushOnce('styles')
    <style>
        .desktop-navigation__link[data-type="link"] {
            & a::after {
                content: '';
                display: block;
                width: 1.5rem;
                height: 2px;
                background-color: currentColor;
                scale: 0 1;
                transform-origin: left;
                transition: scale .2s;
            }

            &.active a::after,
            & a:hover::after {
                scale: 1;
            }
        }

        .main-header:not(data-local-theme="dark") .desktop-navigation__link:has(a:hover) {
            color: var(--color-primary-400);
        }

        .desktop-navigation__link[data-type="button"] a {
            border-color: currentColor;
        }
    </style>
@endPushOnce

@if ($show)
    <li class="desktop-navigation__link transition-colors" data-type="{{ $link['type'] }}">
        <a href="{{ route($link['url']) }}" @class([
            'font-heading',
            'my-5' => $link['type'] == 'link',
            'py-3 px-8 border rounded-full' => $link['type'] == 'button',
            'font-light' => !$isActive,
            'font-medium' => $isActive && $link['type'] == 'link',
        ])>{{ $linkLabel }}</a>
    </li>
@endif
