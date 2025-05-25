@props(['title' => 'Profilo'])

@push('styles')
	<style>
		.dashboard-body {

			@media (min-width: 768px) {
				grid-template-columns: 18rem 1fr
			}
		}

		.dashboard-heading {
			padding-bottom: 0.5rem;
			border-bottom: 1px solid var(--color-surface-500);
		}
	</style>
@endpush

<x-layouts.main :$title>

	{{-- Head --}}
	<x-user-dashboard.head />

	{{-- Body --}}
	<div class="dashboard-body my-8 grid grid-cols-1 content-center gap-12 md:my-20">

		{{-- Navigation --}}
		<x-user-dashboard.navigation />

		{{-- Content --}}
		<div class="grid grid-cols-1 content-start gap-8">
			{{ $slot }}
		</div>

	</div>
</x-layouts.main>
