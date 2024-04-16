@props(['title'])

@php
    $currentRoute = Route::currentRouteName();
    $createRoute = str_replace('index', 'create', $currentRoute);

    $addButton = Route::has($createRoute);
@endphp

<section class="flex gap-4 items-center">

    {{-- Title --}}
    <x-dashboard.typography.main-heading>{!! $title !!}</x-dashboard.typography.main-heading>

    @if ($addButton)
        {{-- Add button --}}
        <x-dashboard.table.title-button />
    @endif

</section>
