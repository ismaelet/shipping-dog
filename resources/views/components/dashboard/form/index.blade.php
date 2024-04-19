@use('App\Helpers\ArrayHelper')

@props(['title', 'type'])

@php
    $routeParameters = request()->route()->parameters ?? null;
    $action = $routeParameters ? 'update' : 'store';
    $actionRouteName = ArrayHelper::explodeLast('.', request()->route()->getName())[0] . '.' . $action;
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
                {{ $slot }}
            </div>

            {{-- Footer --}}
            <div class="flex gap-8">
                @isset($type)
                    @if ($type == 'add')
                        <x-inputs.button type="submit" name="add">
                            {!! embed_icon('add') !!}
                            Aggiungi
                        </x-inputs.button>
                    @else
                        <x-inputs.button type="submit" name="update">
                            {!! embed_icon('save') !!}
                            Salva
                        </x-inputs.button>
                    @endif
                @endisset
            </div>
        </form>
    </div>
</x-layouts.admin-dashboard>
