@use('App\Helpers\ArrayHelper')

@props(['title'])

@php
    $backRouteName = ArrayHelper::explodeLast('.', request()->route()->getName())[0] . '.index';
    $title = str_replace('Modifica:', '<span class=\'font-light\'>Modifica:</span> ', $title);
@endphp

<div class="flex gap-4 items-center">

    @if (Route::has($backRouteName))
        <a class="border rounded w-10 aspect-square hover:text-neutral-700 transition-colors"
            href="{{ route($backRouteName) }}">
            {!! embed_icon('navigation/previous', ['fill-none', 'h-1/3']) !!}
        </a>
    @endif

    <x-dashboard.typography.main-heading>{!! $title !!}</x-dashboard.typography.main-heading>

</div>
