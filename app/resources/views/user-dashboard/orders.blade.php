@use('App\Helpers\EcommerceHelper')
@use('App\Models\Ecommerce\Order')

@php
	$user = auth()->user();
	$pageSize = 10;
	$page = request()->query('page', 1);

	$orderBy = request()->query('order_by', 'id');
	$orderDirection = request()->query('order_direction', 'desc');

	$confirmedOrders = Order::where('user_id', $user->id)
	    ->where('status', '!=', EcommerceHelper::statusFromTag('open'))
	    ->with(
	        'order_items',
	        'order_items.type',
	        'order_items.type.sizes',
	        'order_items.type.colors',
	        'order_items.color',
	        'order_items.size',
	        'order_items.position',
	        'order_items.logo',
	        'order_items.product',
	        'order_items.product.type',
	        'order_items.product_with_trashed',
	    );
	$confirmedOrders =
	    $orderDirection == 'asc' ? $confirmedOrders->orderBy($orderBy) : $confirmedOrders->orderByDesc($orderBy);

	$confirmedOrders = $confirmedOrders->get();

	$confirmedOrdersCount = $confirmedOrders->count();
	$confirmedOrders = $confirmedOrders->forPage($page, $pageSize);

	$columns = ['Ordine', 'Prodotti', 'Totale', 'Stato', 'Data', ''];
	$columns = [
	    ['label' => 'Ordine', 'name' => 'id', 'type' => 'method', 'method' => 'idCode'],
	    ['label' => 'Articoli', 'type' => 'method', 'method' => 'orderItemsString'],
	    ['label' => 'Totale', 'name' => 'price', 'type' => 'price'],
	    ['label' => 'Stato', 'name' => 'status', 'type' => 'method', 'method' => 'statusLabel'],
	    ['label' => 'Data', 'name' => 'created_at', 'type' => 'date'],
	];
@endphp

<x-user-dashboard>
	<x-user-dashboard.panel
		:icon="'cart'"
		:title="'Lista ordini'"
	>

		<div class="overflow-auto">
			@if ($confirmedOrdersCount)
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
					<x-pagination :record-count="$confirmedOrdersCount" />
				</form>
			@else
				<p class="py-8 text-center">Nessun ordine effetuato</p>
			@endif
		</div>

	</x-user-dashboard.panel>
</x-user-dashboard>
