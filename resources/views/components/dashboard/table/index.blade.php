<x-layouts.admin-dashboard>

    <div class="grid gap-8">
        {{-- Title bar --}}
        <x-dashboard.table.title :$title />

        {{-- Table --}}
        <x-table :$columns :$records :$recordCount :$mainOrderBy :$buttons />
    </div>

</x-layouts.admin-dashboard>
