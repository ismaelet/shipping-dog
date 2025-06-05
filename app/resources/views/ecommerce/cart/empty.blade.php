@pushOnce('styles')
	<style>
		.cart-icon {
			width: min(10rem, 30vw);

			svg {
				rotate: -10deg;
			}
		}
	</style>
@endpushOnce

<x-layouts.main>

	<div
		class="grid content-center justify-items-center gap-12 py-20 text-center"
		data-spacing="700"
	>
		<div class="cart-icon border-primary text-primary grid aspect-square place-items-center rounded-full border">
			{!! embed_icon('empty-cart', ['w-1/2']) !!}
		</div>

		<div class="grid gap-6">
			<x-typography.section-heading>Il tuo carrello è vuoto</x-typography.section-heading>

			<p class="text--big">Puoi cercare dei prodotti da acquistare qui</p>
		</div>

		<x-button href="{{ route('shop') }}">Catalogo</x-button>
	</div>

</x-layouts.main>
