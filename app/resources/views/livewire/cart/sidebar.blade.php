@pushOnce('styles')
	<style>
		.cart-sidebar {
			position: fixed;
			inset: 0;
			pointer-events: none;
			z-index: 100;
		}

		.cart-sidebar__backdrop {
			background-color: rgba(0, 0, 0, 0.6);
			position: absolute;
			inset: 0;
			opacity: 0;
			cursor: pointer;
			transition: opacity 0.2s;
		}

		.cart-sidebar.active .cart-sidebar__backdrop {
			opacity: 1;
			pointer-events: all;
		}

		.cart-sidebar__body {
			position: absolute;
			right: 0;
			translate: 100% 0;
			min-width: min(25rem, 100%);
			height: 100%;
			padding: 1rem;
			background-color: var(--color-background-400);
			box-shadow: -0.5rem 0 1rem rgba(0, 0, 0, 0.1);
			pointer-events: all;
			transition: translate 0.2s;
		}

		.cart-sidebar.active .cart-sidebar__body {
			translate: none;
		}

		.cart-sidebar__content {
			display: grid;
			height: 100%;
			grid-template-rows: max-content minmax(0, 1fr) max-content;
		}

		.cart-sidebar__header {
			justify-content: space-between;
			padding-bottom: 1rem;
			border-bottom: var(--border);
		}

		.cart-sidebar__title {
			font-size: var(--text-600);
		}

		.cart-sidebar__close {
			width: 0.75rem;
			cursor: pointer;
		}

		.cart-sidebar .cart-orders {
			overflow-y: auto;
		}

		.cart-sidebar .cart-order {
			grid-template-columns: 0.66rem minmax(0, 1fr) 4.5rem;
			grid-template-areas: "remove data image";
		}

		.cart-sidebar .cart-order_title {
			font-size: 0.9rem;
		}

		.cart-sidebar__recap {
			justify-content: space-between;
			padding-block: 1rem;
			margin-bottom: 1rem;
			border-block: var(--border);
		}
	</style>
@endpushOnce

<section
	class="cart-sidebar js-cart-sidebar"
	x-data="{ show: false }"
	:class="{ active: show }"
	@open-cart-sidebar.window="show = true"
>

	<div
		class="cart-sidebar__backdrop"
		@click="show = false"
	></div>

	<div class="cart-sidebar__body">
		<div class="cart-sidebar__content">
			<!-- Header -->
			<div class="cart-sidebar__header flex-group">
				<x-heading.component-small class="cart-sidebar__title">Carrello</x-heading.component-small>
				<button
					type="button"
					class="cart-sidebar__close"
					@click="show = false"
					aria-label="Chiudi il carrello"
				>
					<x-icon-close />
				</button>
			</div>

			<!-- Order items -->
			<div class="overflow-auto py-4 pr-4">
				@if (!$orderItems || $orderItems->isEmpty())
					<p class="text-center">Il carrello è vuoto</p>
				@else
					@if ($cartId)
						<livewire:cart.order-items-list
							:order-id="$cartId"
							:key="'cart-sidebar-order-items-' . $cartId . '-' . $quantity"
						/>
					@endif
				@endif
			</div>

			<!-- Footer -->
			<div class="cart-sidebar__footer">
				<div class="cart-sidebar__recap flex-group">
					<p>Subtotale:</p>

					<p class="text-neutral-heading font-semibold">
						{{ price($subtotal) }}
					</p>
				</div>

				<div class="grid gap-2">
					<x-button
						variant="secondary"
						size="small"
						@click="show = false"
					>
						Chiudi
					</x-button>

					<x-button
						href="{{ route('cart') }}"
						size="small"
						:disabled="$isEmpty"
					>
						<x-icon-bag />
						Acquista ora
					</x-button>
				</div>
			</div>
		</div>
	</div>
</section>
