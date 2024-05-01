<x-templates.admin-dashboard>

    <div class="grid gap-8">
        {{-- Title bar --}}
        <x-dashboard.title-bar :title="$section->name" :$addButton />

        {{-- Table --}}
        <x-table.layout :$fields :$orderBy :$orderDirection :$records :$buttons :$recordCount :$pageSize
            :$interactiveRows />
    </div>

</x-templates.admin-dashboard>
