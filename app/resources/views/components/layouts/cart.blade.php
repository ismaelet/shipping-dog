@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/cart.css') }}"
	>
@endpushOnce

<x-layouts.legacy-main>

	<div class="full-width bg-surface-500 py-20 text-center sm:py-24">
		<h1 class="text-800 tracking-widest">Ca<span class="txt-c1">rr</span>ello</h1>
	</div>

	<x-cart.flow-bar />

	<div class="content mb-20 grid grid-cols-1">
		{{ $slot }}
	</div>

</x-layouts.legacy-main>
