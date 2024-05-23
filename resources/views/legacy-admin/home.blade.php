<x-layouts.admin-dashboard>

    <x-dashboard.typography.main-heading>Dashboard</x-dashboard.typography.main-heading>

    <div class="grid gap-4 mt-8">
        {{-- Row 1 --}}
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">

            {{-- Labels --}}
            <x-dashboard.widget.labels />

            {{-- Total revenue --}}
            <x-dashboard.widget.total-revenue />

            {{-- Total sales --}}
            <x-dashboard.widget.total-sales />

            {{-- New users --}}
            <x-dashboard.widget.new-users />
        </div>

        {{-- Row 2 --}}
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7">

            {{-- Revenue graph --}}
            <x-dashboard.widget.revenue-graph />

            {{-- Awaiting sellers --}}
            <x-dashboard.widget.awaiting-sellers />

        </div>
    </div>
</x-layouts.admin-dashboard>
