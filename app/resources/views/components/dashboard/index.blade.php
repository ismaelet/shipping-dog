@props(['navigationGroups'])

@pushOnce('styles')
	<style>
		.dashboard {
			display: grid;
			grid-template-columns: max-content minmax(0, 1fr);
		}
	</style>
@endpushOnce

<div class="dashboard">
	<!-- Navigation bar -->
	<x-dashboard.navigation :navigation-groups="$navigationGroups" />
	<!-- Body -->
	<section class="min-h-svh p-8">
		<div class="min-w-64">

			{{ $slot }}

		</div>
	</section>
</div>
