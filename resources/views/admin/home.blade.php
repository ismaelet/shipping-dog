<x-templates.admin-dashboard>

    <x-dashboard.title-bar title="Dashboard" />

    <div class="grid gap-4 mt-8">
        {{-- Row 1 --}}
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">

            {{-- Labels --}}
            <x-dashboard.items.labels />

            {{-- Total revenue --}}
            <x-dashboard.items.total-revenue />

            {{-- Total sales --}}
            <x-dashboard.items.total-sales />

            {{-- New users --}}
            <x-dashboard.items.new-users />
        </div>

        {{-- Row 2 --}}
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7">

            {{-- Revenue graph --}}
            <x-dashboard.items.revenue-graph />

            {{-- Awaiting sellers --}}
            <x-dashboard.items.awaiting-sellers />

        </div>
    </div>
</x-templates.admin-dashboard>
