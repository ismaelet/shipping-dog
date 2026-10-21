<x-user-dashboard :$title>
	<section class="surface border-neutral-border bg-surface-400 rounded-2xl border p-5 sm:p-8">

		<x-user-dashboard.panel-header
			title="La tua wishlist"
			subtitle="Preferiti"
			text="Consulta i prodotti che hai aggiunto alla tua wishlist."
		/>

		<div class="mt-6">
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
		</div>
	</section>
</x-user-dashboard>
