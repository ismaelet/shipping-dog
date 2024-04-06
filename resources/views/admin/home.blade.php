<x-templates.admin>

    <x-admin.typography.section-heading>Dashboard</x-admin.typography.section-heading>

    <div class="grid gap-4 mt-8">
        {{-- Row 1 --}}
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">

            {{-- Labels --}}
            <x-admin.dashboard-items.labels />

            {{-- Total revenue --}}
            <x-admin.dashboard-items.total-revenue />

            {{-- Total sales --}}
            <x-admin.dashboard-items.total-sales />

            {{-- New users --}}
            <x-admin.dashboard-items.new-users />
        </div>

        {{-- Row 2 --}}
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7">

            {{-- Revenue graph --}}
            <x-admin.dashboard-items.revenue-graph />

            {{-- Awaiting sellers --}}
            <x-admin.dashboard-items.awaiting-sellers />

        </div>
    </div>
</x-templates.admin>
