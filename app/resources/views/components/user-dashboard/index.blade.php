@props(['title' => 'Profilo'])

<x-layouts.main :$title>

	{{-- Head --}}
	<x-user-dashboard.head />

	{{-- Body --}}
	<div class="dashboard-body mx-auto my-12 grid w-full max-w-[1248px] grid-cols-1 content-start gap-8 px-2 md:grid-cols-[16.25rem_minmax(0,1fr)]">

		{{-- Navigation --}}
		<x-user-dashboard.navigation />

		{{-- Content --}}
		<div class="grid min-w-0 grid-cols-1 content-start gap-8">
			{{ $slot }}
		</div>

	</div>
</x-layouts.main>
