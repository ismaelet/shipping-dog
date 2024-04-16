@props(['title'])

@php
    $backUrl = route(str_replace(['create', 'edit'], ['index', 'index'], request()->route()->getName()));
    $title = str_replace('Modifica:', '<span class=\'font-light\'>Modifica:</span> ', $title);
@endphp

<div class="flex gap-4 items-center">

    <a class="border rounded w-10 aspect-square hover:text-neutral-700 transition-colors" href="{{ $backUrl }}">
        {!! embed_icon('navigation/previous', ['fill-none', 'h-1/3']) !!}
    </a>

    <x-dashboard.typography.main-heading>{!! $title !!}</x-dashboard.typography.main-heading>

</div>
