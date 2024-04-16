@props(['record', 'column'])

@php
    $editRoute = str_replace('index', 'edit', request()->route()->getName());
    $modelName = Str::singular($record->getTable());
    $editRoute = Route::has($editRoute) ? route($editRoute, [$modelName => $record->id]) : null;
@endphp

<td class="align-middle">

    @if (isset($column['name']))
        @if ($editRoute)
            <a href="{{ $editRoute }}">
        @endif

        <div class="p-4 font-medium">
            <x-table.cell-value :$record :$column />
        </div>

        @if ($editRoute)
            </a>
        @endif
    @else
        <x-table.buttons :$record :$editRoute />
    @endif

</td>
