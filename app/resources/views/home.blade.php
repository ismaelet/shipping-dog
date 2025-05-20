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

<x-layouts.main :$locationTag>
	<div class="span-full-width text-primary">
		@include('home/slider')
		@include('home/categories')
		@include('home/popular')
		@include('home/shops')
		@include('home/designs')
		@include('home/client-pics')
	</div>
</x-layouts.main>
