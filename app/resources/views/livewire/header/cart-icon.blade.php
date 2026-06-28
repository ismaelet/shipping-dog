@pushOnce('styles')
	<style>
		.h__nav-icon {
			display: flex;
			justify-content: center;
			position: relative;
			width: 1.25rem;
			height: 1.25rem;
		}

		.h__nav-icon:hover {
			color: var(--color-primary-500);
		}

		.h__nav-icon--cart::after {
			content: attr(data-quantity);
			position: absolute;
			right: 0;
			bottom: 0;
			translate: 30% 30%;
			width: 1.3em;
			background-color: var(--color-primary-400);
			border-radius: 50%;
			color: #fff;
			font-size: 0.875rem;
			line-height: 1.3em;
			text-align: center;
		}

		.h__nav-icon--cart[data-quantity="0"]::after {
			display: none;
		}
	</style>
@endpushOnce

<button
	type="button"
	class="h__nav-icon h__nav-icon--cart {{ $quantity ? 'active' : '' }} cursor-pointer transition-colors"
	data-quantity="{{ $quantity }}"
	aria-label="Apri il carrello, {{ $quantity }} articoli"
	x-data
	@click="$dispatch('open-cart-sidebar')"
>
	<x-icon-bag />
</button>
