<x-layouts.admin-dashboard>

	<x-dashboard.typography.main-heading>Dashboard</x-dashboard.typography.main-heading>

	<div class="mt-8 grid gap-4">
		{{-- Row 1 --}}
		<div class="grid">

			{{-- Labels --}}
			<x-dashboard.widget.labels />
		</div>

		{{-- Row 2 --}}
		<div class="grid">

			{{-- Awaiting sellers --}}
			<x-dashboard.widget.awaiting-sellers />

		</div>
	</div>
</x-layouts.admin-dashboard>
