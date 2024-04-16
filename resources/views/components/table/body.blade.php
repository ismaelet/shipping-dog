@props(['columns', 'records'])

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
        <tr class="table-row border-t transition-colors hover:bg-surface-400">

            @foreach ($columns as $column)
                <x-table.cell :$column :$record />
            @endforeach

        </tr>
    @endforeach

</tbody>
