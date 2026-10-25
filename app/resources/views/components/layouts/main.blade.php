@props([
    'title' => null,
    'description' => null,
    'thumbnail' => null,
    'location' => null,
    'colorScheme' => null,
    'pageClass' => null,
])

@push('styles')
	<style>
		.main-content {
			display: grid;
			grid-template-areas:
				'header'
				'main';
			grid-template-columns: minmax(0, 1fr);
			grid-template-rows: max-content 1fr;
			min-height: 100svh;
		}

		.header-container {
			grid-area: header;
		}

		main {
			grid-area: main;
		}
	</style>
@endpush

<x-layouts.app
	:$title
	:$description
	:$location
	:$thumbnail
	:$colorScheme
>
	{{-- Page content --}}
	<div @class(['main-content min-h-svh', $pageClass])>
		{{-- Header --}}
		<x-header />

		{{-- Main --}}
		<main>
			<div class="content-grid min-h-full">
				{{ $slot }}
			</div>
		</main>
	</div>

	{{-- Footer --}}
	<x-footer />
</x-layouts.app>
