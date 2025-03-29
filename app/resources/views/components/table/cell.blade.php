@aware(['rowUrl', 'rowMethodUrl'])

@props(['record', 'column'])

@php
	$modelName = Str::singular($record->getTable());
	$editRoute = null;
	$currentRoute = request()->route()->getName();

	if ($rowUrl) {
	    $editRoute = str_replace('id', $record->id, $column['url']);
	} elseif ($rowMethodUrl) {
	    $editRoute = $record->{$rowMethodUrl}();
	} elseif (strpos(request()->route()->getName(), '.index') !== false) {
	    $editRoute = str_replace('index', 'edit', $currentRoute);

	    $editRoute = Route::has($editRoute)
	        ? route($editRoute, [$modelName => $record->id, 'index_parameters' => json_encode(request()->all())])
	        : null;
	}
@endphp

<td class="table-cell align-middle">

	@if (isset($column['label']))
		@if ($editRoute)
			<a href="{{ $editRoute }}">
		@endif

		<div class="p-4 font-medium">
			<x-table.cell-value
				:$record
				:$column
			/>
		</div>

		@if ($editRoute)
			</a>
		@endif
	@else
		<x-table.buttons
			:$record
			:$editRoute
		/>
	@endif

</td>
