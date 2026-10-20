@pushOnce('styles')
	<style>
		.header-cart-icon::after {
			content: attr(data-quantity);
			position: absolute;
			left: 50%;
			top: 0;
			width: 1.125rem;
			background-color: var(--color-primary-400);
			border-radius: 50%;
			color: #fff;
			font-size: var(--text-sm);
			line-height: 1.125rem;
			text-align: center;
		}

		.header-cart-icon[data-quantity="0"]::after {
			display: none;
		}
	</style>
@endpushOnce

<button
	type="button"
	class="header-cart-icon header-icon-wrapper {{ $quantity ? 'active' : '' }} relative cursor-pointer transition-colors"
	data-quantity="{{ $quantity }}"
	aria-label="Apri il carrello, {{ $quantity }} articoli"
	x-data
	@click="$dispatch('open-cart-sidebar')"
>
	<x-icon-bag />
</button>
