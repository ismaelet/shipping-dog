@props(['fields', 'orderBy', 'orderDirection'])

<thead class="">
    <tr class="">
        @foreach ($fields as $field)
            <x-table.head-item :$field :$orderBy :$orderDirection />
        @endforeach
    </tr>
</thead>
