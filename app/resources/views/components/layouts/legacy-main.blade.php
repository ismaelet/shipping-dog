@props(['title' => null, 'theme' => null, 'headerConfig' => [], 'locationTag' => null])

@push('styles')
	<script
		src="https://code.jquery.com/jquery-3.7.1.min.js"
		integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
		crossorigin="anonymous"
	></script>
	<link
		rel="stylesheet"
		href="{{ asset('legacy.css') }}"
	>
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

		main {
			grid-area: main;
		}
	</style>
@endpush

@pushOnce('scripts')
	<script src="{{ asset('legacy.js') }}"></script>
@endpushOnce

<x-layouts.app
	:$title
	:$theme
>
	<div class="main-content">
		<x-navigation.header
			:config="$headerConfig"
			:$locationTag
		/>

		<main>
			<div class="content-grid min-h-full">
				{{ $slot }}
			</div>
		</main>
	</div>

	<x-navigation.footer />
</x-layouts.app>
