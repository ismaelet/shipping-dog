@pushOnce('styles')
	<style>
		/* Products */
		#products {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(10rem, 1fr));
			gap: 2rem;
		}

		#products img {
			object-fit: cover;
			border-radius: 10px;
			box-shadow: 5px 10px 1rem rgba(0, 0, 0, 0.1);
			width: 100%;
			aspect-ratio: 1 / 1;
		}

		#products .shop {
			color: var(--text-color);
			font-size: 0.8rem;
		}
	</style>
@endpushOnce

<x-user-dashboard :$title>
	<x-user-dashboard.panel
		:icon="'love'"
		:$title
	>

		@if ($likes->count())
			<div class="main-wrap pad-t-4 pad-b-5">
				<livewire:wishlist />
			</div>
		@else
			<div class="main-wrap">
				<div
					id="empty-box"
					class="text-center"
				>

					<div
						id="empty-icon"
						class="inlb mar-b-2"
					></div>

					<div class="grid grid-cols-1 justify-items-center gap-8">
						<p id="empty-txt">Nessuna grafica nella wishlist. Cerca nel nostro negozio un prodotto che possa piacerti.</p>

						<x-button href="{{ route('shop') }}">
							Vai al negozio
						</x-button>
					</div>

				</div>
			</div>
		@endif


	</x-user-dashboard.panel>
</x-user-dashboard>
