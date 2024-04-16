@props([
    'searchBar' => true,
    'mainOrderBy',
    'columns',
    'records',
    'recordCount',
    'buttons',
])

@pushOnce('styles')
    <style>
        .table-container {
            grid-template-areas:
                "search-bar"
                "top-pagination"
                "table"
                "bottom-pagination";

            >form {
                display: contents;
            }
        }

        .search-bar {
            grid-area: search-bar;
        }

        .pagination {
            grid-area: top-pagination;
        }

        .pagination:last-child {
            grid-area: bottom-pagination;
        }

        .table {
            grid-area: table;
        }
    </style>
@endpushOnce

<div class="table-container grid gap-8">

    <form action="" method="GET">
        <input type="hidden" name="order_by" value="{{ request()->order_by }}">
        <input type="hidden" name="order_direction" value="{{ request()->order_direction }}">

        @if ($searchBar)
            {{-- Search bar --}}
            <x-table.search-bar />
        @endif

        {{-- Top pagination --}}
        <x-pagination :$recordCount />

        {{-- Bottom pagination --}}
        <x-pagination :$recordCount />
    </form>

    {{-- Table --}}
    <section class="table p-8 border rounded">
        <table class="w-full">
            {{--  Table head  --}}
            <x-table.header :$columns />

            {{-- Table body --}}
            <x-table.body :$records :$columns />
        </table>
    </section>
</div>
