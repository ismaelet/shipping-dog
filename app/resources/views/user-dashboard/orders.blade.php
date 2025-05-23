<x-user-dashboard>
	<x-user-dashboard.panel
		:icon="'cart'"
		:title="'Lista ordini'"
	>

		<div class="overflow-auto">
			@if ($confirmedOrders->count())
				<table>
					{{--  Table head  --}}
					<x-table.header :$columns />

					{{-- Table body --}}
					<x-table.body
						:records="$confirmedOrders"
						:$columns
						row-method-url="recapUrl"
					/>
				</table>

				<form
					action=""
					method="GET"
					class="mt-8"
				>
					{{-- Bottom pagination --}}
					<x-pagination :record-count="$confirmedOrders->count()" />
				</form>
			@else
				<p class="py-8 text-center">Nessun ordine effetuato</p>
			@endif
		</div>

	</x-user-dashboard.panel>
</x-user-dashboard>
