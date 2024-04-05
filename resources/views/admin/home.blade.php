<x-templates.admin>

    <x-typography.section-heading>Dashboard</x-typography.section-heading>

    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4 mt-8">

        {{-- Labels --}}
        <x-admin.dashboard-items.label />

        {{-- Total revenue --}}
        <x-admin.dashboard-items.total-revenue />

        {{-- Total sales --}}
        <x-admin.dashboard-items.total-sales />

        {{-- New users --}}
        <x-admin.dashboard-items.new-users />
    </div>
</x-templates.admin>
