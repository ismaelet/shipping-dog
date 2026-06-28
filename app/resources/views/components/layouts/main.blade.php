@props(['title' => null, 'theme' => null, 'headerConfig' => [], 'locationTag' => null])

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

		main {
			grid-area: main;
		}
	</style>
@endpush

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
	<livewire:cart.sidebar />
</x-layouts.app>
