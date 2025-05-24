@props(['seller'])

@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/seller-dashboard.css') }}"
	>
@endpushOnce

<x-layouts.legacy-main>

	<div class="span-full-width dashboard">
		<x-seller-dashboard.navigation />

		<div
			id="content"
			class="content-grid overflow-hidden pb-20 pt-8"
		>
			<div class="breakout">

				{{ $slot }}

			</div>
		</div>
	</div>

</x-layouts.legacy-main>
