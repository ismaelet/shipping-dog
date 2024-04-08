<x-templates.admin-dashboard>

    <x-templates.dashboard.dashboard-heading>Dashboard</x-templates.dashboard.dashboard-heading>

    <div class="grid gap-4 mt-8">
        {{-- Row 1 --}}
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">

            {{-- Labels --}}
            <x-admin.labels />

            {{-- Total revenue --}}
            <x-admin.total-revenue />

            {{-- Total sales --}}
            <x-admin.total-sales />

            {{-- New users --}}
            <x-admin.new-users />
        </div>

        {{-- Row 2 --}}
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7">

            {{-- Revenue graph --}}
            <x-admin.revenue-graph />

            {{-- Awaiting sellers --}}
            <x-admin.awaiting-sellers />

        </div>
    </div>
</x-templates.admin-dashboard>
