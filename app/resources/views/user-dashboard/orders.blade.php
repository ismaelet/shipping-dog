<x-user-dashboard :$title>
	<section class="surface border-neutral-border bg-surface-400 rounded-2xl border p-5 sm:p-8">
		<x-user-dashboard.panel-header
			title="I tuoi ordini"
			subtitle="Acquisti"
			text="Controlla lo stato delle spedizioni e consulta gli acquisti passati."
			:buttonLabel="$totalCount ? 'Scarica storico' : null"
			:buttonUrl="$totalCount ? route('user-dashboard.orders.download') : null"
		/>

		@if ($confirmedOrders->count())
			<div class="overflow-x-auto">
				<table class="min-w-160 w-full text-left text-sm">
					<thead>
						<tr class="text-neutral-text text-sm font-semibold uppercase tracking-wider">
							<th class="py-4 pr-4 font-semibold">Ordine</th>
							<th class="py-4 pr-4 font-semibold">Articoli</th>
							<th class="py-4 pr-4 font-semibold">Totale</th>
							<th class="py-4 pr-4 font-semibold">Stato</th>
							<th class="py-4 pr-4 font-semibold">Data</th>
							<th class="w-6"></th>
						</tr>
					</thead>
					<tbody>
						@foreach ($confirmedOrders as $order)
							<x-user-dashboard.order-row :$order />
						@endforeach
					</tbody>
				</table>
			</div>

			<form
				action=""
				method="GET"
				class="mt-6"
			>
				<x-pagination
					:record-count="$totalCount"
					:page-size="$pageSize"
				/>
			</form>
		@else
			<p class="text-neutral-text py-10 text-center text-sm">Nessun ordine effettuato</p>
		@endif
	</section>
</x-user-dashboard>
