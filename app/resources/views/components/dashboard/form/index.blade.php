@use('App\Helpers\StringHelper')

@props(['title', 'type'])

@php
    $routeParameters = request()->route()->parameters ?? null;
    $action = $routeParameters ? 'update' : 'store';
    $actionRouteName = StringHelper::explodeLast('.', request()->route()->getName())[0] . '.' . $action;
    $actionRoute = $routeParameters ? route($actionRouteName, $routeParameters) : route($actionRouteName);
@endphp

<x-layouts.admin-dashboard>
    <div class="grid gap-12">
        {{-- Title --}}
        <x-dashboard.form.title :$title />

        <form action="{{ $actionRoute }}" method="POST" class="grid gap-8">
            {{-- Request parameters --}}
            @csrf
            @method($routeParameters ? 'PATCH' : 'POST')

            <input type="hidden" name="index_parameters" value="{{ request()->index_parameters }}">

            {{-- Body --}}
            <div class="grid grid-cols-1">
                {{ $body }}
            </div>

            {{-- Footer --}}
            <div class="flex gap-8">
                {{ $footer ?? '' }}
            </div>
        </form>
    </div>
</x-layouts.admin-dashboard>
