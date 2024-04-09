@props([
    'fields',
    'orderBy' => 'id',
    'orderDirection' => 'desc',
    'records',
    'buttons',
    'recordCount',
    'searchBar' => true,
    'interactiveRows' => true,
    'pageSize' => 50,
])

<form class="grid gap-8" method="GET">

    @if ($searchBar)
        {{-- Search bar --}}
        <x-table.search-bar />
    @endif

    {{-- Top pagination --}}
    <x-pagination.layout :$recordCount :$pageSize />

    {{-- Table --}}
    <x-table.table :$fields :$orderBy :$orderDirection :$records :$buttons :$interactiveRows />

    {{-- Bottom pagination --}}
    <x-pagination.layout :$recordCount :$pageSize />
</form>
