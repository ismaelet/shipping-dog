@props(['columns', 'records', 'rowUrl' => null, 'rowMethodUrl' => null])

@pushOnce('styles')
	<style>
		[data-color-scheme="light"] {
			.table-row:hover {
				background-color: var(--color-surface-500);
			}
		}
	</style>
@endpushOnce

<tbody>
	@foreach ($records as $record)
		<tr class="hover:bg-surface-400 table-row border-t transition-colors">

			@foreach ($columns as $column)
				<x-table.cell
					:$column
					:$record
				/>
			@endforeach

		</tr>
	@endforeach

</tbody>
