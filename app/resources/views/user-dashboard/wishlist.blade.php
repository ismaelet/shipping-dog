<x-user-dashboard :$title>
	<x-user-dashboard.panel
		:icon="'heart'"
		:meta="$likes->count() . ($likes->count() === 1 ? ' prodotto' : ' prodotti')"
		:$title
	>

		@if ($likes->count())
			<div>
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
