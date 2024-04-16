@aware(['mainOrderBy', 'orderDirection'])

@props(['column'])

@php
    $hasName = isset($column['name']);
    $mainOrderBy = $mainOrderBy ?? 'id';
    $orderBy = request()->order_by ?: $mainOrderBy;
    $orderDirection = request()->order_direction ?: 'desc';
    $active = $hasName && $orderBy == $column['name'];
    $newOrderDirection = $active && $orderDirection == 'desc' ? 'asc' : 'desc';
@endphp

@pushOnce('styles')
    <style>
        .table-head-item {
            color: var(--color-neutral-300);

            [data-color-scheme="light"] & {
                color: var(--color-neutral-350);
            }
        }
    </style>
@endpushOnce

<th @class([
    'table-head-item',
    'h-12',
    'px-4',
    'text-left',
    'align-middle',
    'cursor-pointer',
    'font-medium',
    'hover:text-primary',
    'transition-colors',
    'text-center' =>
        isset($column['type']) && in_array($column['type'], ['bool']),
    'js-submit-form',
])>
    @if ($hasName)
        <form method="GET">
            <input type="hidden" name="order_by" value="{{ $column['name'] }}">
            <input type="hidden" name="order_direction" value="{{ $newOrderDirection }}">
            <input type="hidden" name="search" value="{{ request()->search }}">

            {{ $column['label'] }}

            @if ($active)
                {!! $orderDirection == 'desc' ? '&#x25BE;' : '&#x25B4;' !!}
            @endif
        </form>
    @endif
</th>
