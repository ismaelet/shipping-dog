@props(['seller' => auth()->user()->seller])

@pushOnce('styles')
	<style>
		.dashboard-panel {
			padding: 2rem;
		}

		.form-foot {
			border-top: var(--border);
			text-align: center;
			padding: 1rem 0;
			margin-top: 2rem;
		}
	</style>
@endPushOnce

<x-layouts.main>
	<div class="breakout seller-dashboard-layout grid grid-cols-1 gap-8 lg:grid-cols-[15rem_minmax(0,1fr)]">
		<x-seller-dashboard.navigation :$seller />

		<div
			id="content"
			class="min-w-0 py-12"
		>
			{{ $slot }}
		</div>
	</div>

</x-layouts.main>
