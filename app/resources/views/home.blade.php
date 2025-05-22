@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/home.css') }}"
	>
@endpushOnce

@pushOnce('scripts')
	<script src="{{ asset('js/legacy/home.js') }}"></script>
@endpushOnce

<x-layouts.legacy-main :$locationTag>
	<div class="span-full-width text-primary">
		@include('home/slider')
		@include('home/categories')
		@include('home/popular')
		@include('home/shops')
		@include('home/designs')
		@include('home/client-pics')
	</div>
</x-layouts.legacy-main>
