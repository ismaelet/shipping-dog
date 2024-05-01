<x-layouts.admin-dashboard>

    <div class="grid gap-8">
        {{-- Title bar --}}
        <x-dashboard.table.title :$title />

        @session('status')
            {{-- Status alert --}}
            <x-inputs.alert-success>{{ $value }}</x-inputs.alert-success>
        @endsession

        @session('error')
            {{-- Error alert --}}
            <x-inputs.alert-error>{{ $value }}</x-inputs.alert-error>
        @endsession

        {{-- Table --}}
        <x-table :$columns :$records :$recordCount :$mainOrderBy :$buttons />
    </div>

</x-layouts.admin-dashboard>
