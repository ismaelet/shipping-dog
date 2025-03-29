@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('legacy.css') }}"
	>
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/home.css') }}"
	>
@endpushOnce

<x-layouts.main>
	<div class="span-full-width">
		@include('home/slider')
		@include('home/categories')
		@include('home/popular')
		@include('home/shops')
		@include('home/designs')
		@include('home/client-pics')
	</div>
</x-layouts.main>
