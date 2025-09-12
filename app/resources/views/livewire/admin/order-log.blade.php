@php

	use App\Helpers\EcommerceHelper;
	use App\Models\Ecommerce\Order;
	use App\Models\Framework\Configuration;

	$request = request();

	// Get the start from time
	$startFromTime = null;

	if ($request->start_from) {
	    $startFromOrderId = explode(' - ', $request->start_from)[0];
	    $startFromOrder = Order::find($startFromOrderId);

	    $startFromTime = $startFromOrder->created_at;
	}

	$startFromTime = $startFromTime ?: Configuration::where('name', 'order_log_start_from')->value('value');

	// Fetch the orders
	$orders = Order::where('created_at', '>=', $startFromTime)->where(
	    'status',
	    '>',
	    EcommerceHelper::statusFromTag('received'),
	);

	if (!$request->show_all) {
	    $orders = $orders->where('status', '<', EcommerceHelper::statusFromTag('awaiting-shipping'));
	}

	$orders = $orders
	    ->with(
	        'user',
	        'user.seller',
	        'address',
	        'shipping_method',
	        'coupon',
	        'change_request',
	        'order_items',
	        'order_items.type',
	        'order_items.type.sizes',
	        'order_items.type.colors',
	        'order_items.product',
	        'order_items.product_with_trashed',
	        'order_items.product.type',
	        'order_items.product.type.sizes',
	        'order_items.product.type.colors',
	        'order_items.color',
	        'order_items.size',
	        'order_items.position',
	        'order_items.logo',
	        'payment_method',
	    )
	    ->oldest()
	    ->get();

	$ordersFilter = $orders->first() ? [['orders.created_at', '>=', $orders->first()->created_at]] : [];
	$showAll = (bool) $request->show_all;

@endphp

<script>
	document.addEventListener('DOMContentLoaded', function() {
		/*
		 * Show the apply button
		 */
		const searchInput = document.querySelector('[name="start_order_id"]')
		const startingCartName = searchInput.value

		searchInput.addEventListener('input', function() {
			if (this.value !== startingCartName) {
				document.querySelector('.js-apply-search').classList.remove('hidden')
				document.querySelector('.js-apply-search').classList.add('contents')
			} else {
				document.querySelector('.js-apply-search').classList.remove('contents')
				document.querySelector('.js-apply-search').classList.add('hidden')
			}
		})

		/*
		 * Copy the order_items content
		 */
		const copyAllButton = document.querySelector('.js-copy-order_items');

		// Copy from cart set in the header
		if (copyAllButton) {
			copyAllButton.addEventListener('click', async function() {
				cartId = document.querySelector('[name="start_copy_order_id"]').value

				copyorder_itemsContent(cartId)
			})
		}

		// Copy from specific cart
		document.querySelectorAll('.js-copy-from-cart').forEach((button) => {
			button.addEventListener('click', async function() {
				cartId = this.getAttribute('data-id')

				copyorder_itemsContent(cartId)
			})
		})

		// Copy the order_items content
		function copyorder_itemsContent(startingCartId) {
			let order_items = []
			let order = document.querySelector(`.js-order[data-id="${startingCartId}"]`);

			order_items.push(order);

			while (nextOrder = selectNextOrder(order)) {
				order = nextOrder;

				if (order) {
					order_items.push(order);
				}
			}

			// Get the HTML content of the entire document
			let text = '';

			order_items.forEach((order) => {
				order.querySelectorAll('.js-to-copy').forEach((element) => {
					text += element.innerHTML + '<br>';
				})

				text += '--------<br>';
			})

			copyRichTextToClipboard(text)
		}

		function selectNextOrder(order) {
			const nextSibling = order.closest('article').nextElementSibling

			if (nextSibling) {
				return nextSibling.querySelector('.js-order');
			}

			return null;
		}

		async function copyRichTextToClipboard(html) {
			try {
				await navigator.clipboard.write([
					new ClipboardItem({
						'text/html': new Blob([html], {
							type: 'text/html'
						}),
					}),
				])
				console.log('Rich text copied to clipboard')
				return true
			} catch (err) {
				console.error('Failed to copy rich text: ', err)
				return false
			}
		}
	})
</script>


<div class="grid gap-8">

	{{-- Recap form --}}
	<x-dashboard.form.panel :title="'Mostrando ' . $orders->count() . ' ordini'">
		<form
			method="GET"
			action=""
		>
			<div class="grid gap-8">

				@if ($orders->first())
					<x-form.group>
						<x-label>Copia a partire da:</x-label>
						<div class="flex gap-4">
							<x-inputs.search-input
								search="orders"
								stored-name="start_copy_order_id"
								stored-value="{{ $orders->first()->id }}"
								shown-value="{{ $orders->first()->id . ' - ' . $orders->first()->address->full_name }}"
								placeholder="Ordine da cui copiare"
								:where="$ordersFilter"
								required
								class="grow"
							/>

							<x-button class="js-copy-order_items">
								<x-heroicon-o-clipboard-document-list class="text-white" />
								Copia il log
							</x-button>
						</div>
					</x-form.group>
				@endif

				<x-form.group>
					<x-label>Parti dall'ordine:</x-label>
					<div class="flex gap-4">
						<x-input
							name="start_from"
							class="grow"
							value="{{ $orders->first()?->id ? $orders->first()->id . ' - ' . $orders->first()->address->full_name : '' }}"
							placeholder="Ordine da cui partire"
							required
						/>

						<x-button
							class=""
							type="submit"
							color="secondary"
						>
							Carica
						</x-button>
					</div>
				</x-form.group>

				@if ($orders->count())
					<x-form.group>
						<div class="flex gap-4">

							<div class="contents">
								@csrf

								<x-button
									type="submit"
									color="danger"
									variant="secondary"
									formaction="{{ route('admin.orders-log.clean') }}"
								>
									<x-heroicon-o-trash />
									Pulisci il log
								</x-button>
							</div>
						</div>
					</x-form.group>
				@endif

				<x-form.group>
					<div action="">
						<x-checkbox
							name="show_all"
							text="Mostra anche gli ordini pronti o spediti"
							checked="{{ $showAll }}"
							@change="$event.target.closest('form').submit()"
						/>
					</div>
				</x-form.group>
			</div>
		</form>
	</x-dashboard.form.panel>

	{{-- Orders --}}
	<div class="grid grid-cols-1 gap-8">
		@if ($orders->count())
			@foreach ($orders as $order)
				@php
					$title = $order->idCode() . ' ' . ($order->address->full_name ?? '');
					$title =
					    $order->user && $order->user->isSeller() ? $title . ' - <span class="text-error">Venditore</span>' : $title;
				@endphp

				<x-dashboard.form.panel
					:title="$title"
					id="orders"
					class="order log js-order"
					data-id="{{ $order->id }}"
				>

					{{-- Order header --}}
					<p class="js-to-copy">[{{ $order->id }} - {{ $order->createdAtString() }} -
						{{ $order->payment_method->name }}]
					</p>

					{{-- Address --}}
					@if ($order->address)
						{{-- If found --}}
						<div>
							<p class="js-to-copy">{!! implode('<br>', $order->address->full_address) !!}</p>
							<p>---</p>
							<p>{{ $order->address->email }}</p>
							<p>{{ $order->address->phone }}</p>
						</div>
					@else
						{{-- If error --}}
						@php
							$order->has_errors = 1;
							$order->save();
						@endphp

						<p class="text-error">!!! Errore nell'indirizzo !!!</p>
					@endif

					{{-- Coupon --}}
					@if ($order->coupon)
						<p>Sconto: <span class="font-bold">{{ $order->code }}</span></p>
					@endif

					{{-- Order Items --}}
					<div class="js-to-copy">
						@foreach ($order->order_items as $orderItem)
							<p>
								@if ($orderItem->inventory == 1)
									<span class="text-green font-bold">! IN INVENTARIO !</span>
								@endif

								@if ($orderItem->inventory == 2)
									<span class="text-dark-green font-bold">! SIMILE IN INVENTARIO !</span>
								@endif

								<a
									href="{{ $orderItem->product_with_trashed->fullUrl($orderItem->color_id, $orderItem->position_id) }}"
									target="_blank"
								>
									{{ $orderItem->full_name }}
								</a>
							</p>
						@endforeach
					</div>

					{{-- Shipping --}}
					<p class="js-to-copy">{!! $order->shipping_method->name !!}, {!! $order->shipping_method->deliveryDateString($order->created_at, $order->hasLongDelivery()) !!}</p>

					{{-- Price --}}
					@if (!$order->change_request)
						<p class="js-to-copy">Totale: <strong>{!! $order->priceString() !!}</strong></p>
					@else
						<p class="text-blue js-to-copy font-bold">CAMBIO TAGLIA</p>
					@endif

					<x-button
						class="js-copy-from-cart"
						data-id="{{ $order->id }}"
						variant="secondary"
					>
						<x-heroicon-o-clipboard class="text-white" />
						Copia a partire da qui
					</x-button>
				</x-dashboard.form.panel>
			@endforeach
		@else
			<x-dashboard.form.panel>
				<p class="py-8">
					Nessun ordine registrato dopo le <span
						class="font-bold text-neutral-700">{{ new DateTime($startFromTime)->format('H:i:s') }}</span>
					del <span class="font-bold text-neutral-700">{{ new DateTime($startFromTime)->format('d/m/Y') }}</span>
				</p>
			</x-dashboard.form.panel>
		@endif
	</div>
</div>
