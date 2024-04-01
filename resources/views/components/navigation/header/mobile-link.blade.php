@php
    $show = isset($link['auth']) ? $link['auth'] == Auth::check() : true;
    $username = Auth::user() ? Auth::user()->first_name : null;
    $linkLabel = populate_variables($link['label'], ['username' => $username]);
@endphp

@pushOnce('styles')
    <style>
        .mobile-navigation__link {
            letter-spacing: 0.05em;
            transition: color 0.2s;
        }
    </style>
@endPushOnce

@if ($show)
    <li class="mobile-navigation__link font-heading text-lg text-neutral-700">
        <a href="{{ route($link['url']) }}">{{ $linkLabel }}</a>
    </li>
@endif
