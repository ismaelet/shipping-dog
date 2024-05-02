@aware(['mainOrderBy', 'orderDirection'])

@props(['column'])

@php
	$hasName = isset($column['name']);
	$mainOrderBy = $mainOrderBy ?? 'id';
	$orderItemBy = request()->order_by ?: $mainOrderBy;
	$orderItemDirection = request()->order_direction ?: 'desc';
	$active = $hasName && $orderItemBy == $column['name'];
	$newOrderDirection = $active && $orderItemDirection == 'desc' ? 'asc' : 'desc';
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
	'font-medium',
	'text-center' =>
		isset($column['type']) && in_array($column['type'], ['bool']),
	'cursor-pointer' => isset($column['name']),
	'hover:text-primary' => isset($column['name']),
	'transition-colors' => isset($column['name']),
	'js-submit-form' => isset($column['name']),
])>
	@isset($column['label'])
		@if ($hasName)
			<form method="GET">
				<input
					type="hidden"
					name="order_by"
					value="{{ $column['name'] }}"
				>
				<input
					type="hidden"
					name="order_direction"
					value="{{ $newOrderDirection }}"
				>
				<input
					type="hidden"
					name="search"
					value="{{ request()->search }}"
				>

				{{ $column['label'] }}

				@if ($active)
					{!! $orderItemDirection == 'desc' ? '&#x25BE;' : '&#x25B4;' !!}
				@endif
			</form>
		@else
			{{ $column['label'] }}
		@endif
	@endisset
</th>
