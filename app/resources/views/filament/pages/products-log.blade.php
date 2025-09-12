@php

	use App\Helpers\EcommerceHelper;
	use App\Models\Ecommerce\Order;
	use App\Models\Ecommerce\OrderItem;
	use App\Models\Framework\Configuration;

	$request = request();

	// Get the start from time
	$startFromTime = null;

	if ($request->start_from) {
	    $startFromOrderId = explode(' - ', $request->start_from)[0];
	    $startFromOrder = Order::find($startFromOrderId);

	    $startFromTime = $startFromOrder->created_at;
	}

	$startFromTime = $startFromTime ?: Configuration::where('name', 'product_log_start_from')->value('value');

	// Fetch the orders
	$orders = Order::where('created_at', '>=', $startFromTime)->where(
	    'status',
	    '>',
	    EcommerceHelper::statusFromTag('received'),
	);

	$startFromCart = $orders->exists() ? $orders->oldest()->first() : null;

	// Get the orders
	if ($startFromCart) {
	    $filteredOrderItems = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
	        ->join('addresses', 'addresses.order_id', '=', 'orders.id')
	        ->join('stores', 'stores.id', '=', 'order_items.seller_id')
	        ->join('products', 'products.id', '=', 'order_items.product_id')
	        ->join('product_types', 'product_types.id', '=', 'order_items.type_id')
	        ->where('orders.status', '>', EcommerceHelper::statusFromTag('received'))
	        // ->where('orders.status', '<', EcommerceHelper::statusFromTag('awaiting-shipping'))
	        ->where('orders.created_at', '>=', $startFromTime)
	        ->select(
	            'orders.id as order_id',
	            'orders.created_at as confirmedTime',
	            'order_items.*',
	            'stores.name as storeName',
	            'addresses.full_name as customerName',
	            'products.name as productName',
	            'product_types.name as productTypeName',
	        );

	    if (!$request->show_all) {
	        $filteredOrderItems = $filteredOrderItems->where(
	            'orders.status',
	            '<',
	            EcommerceHelper::statusFromTag('awaiting-shipping'),
	        );
	    }

	    $filteredOrderItems = $filteredOrderItems
	        ->with(
	            'type',
	            'type.sizes',
	            'type.colors',
	            'color',
	            'size',
	            'position',
	            'logo',
	            'product_with_trashed',
	            'product_with_trashed.type',
	            'product_with_trashed.type.colors',
	            'product_with_trashed.type.sizes',
	        )
	        ->get();

	    // Get the first cart
	    $firstCart = $filteredOrderItems->sortBy('confirmedTime')->first();
	}

	$ordersCount = $orders->count();
	$orderItemsCount = $filteredOrderItems?->count() ?? 0;
	$firstCart = $firstCart ?? null;
	$filteredOrderItems = $filteredOrderItems ?? null;

	$showAll = (bool) $request->show_all;

@endphp

@pushOnce('styles')
	<style>
		:root {
			--color-primary-400: rgb(var(--primary-400));
			--color-primary-500: rgb(var(--primary-500));
			--color-secondary-400: rgb(var(--secondary-400));
			--color-secondary-500: rgb(var(--secondary-500));
			--color-error-400: rgb(var(--danger-400));
			--color-error-500: rgb(var(--danger-500));
			--color-neutral-400: rgb(var(--gray-400));
			--border-color: rgb(var(--gray-600));

			--border-width: 1px;
		}

		.surface {
			padding: 2rem;
			border-radius: 1rem;
			border: 1px solid #555;
		}

		button svg {
			width: 1em;
		}

		.h-8 {
			height: 2rem;
		}

		.h-10 {
			height: 2.5rem;
		}

		.h-12 {
			height: 3rem;
		}
	</style>
@endpushOnce

@pushOnce('scripts')
	<script defer>
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
			 * Copy all the order_items to the clipboard
			 */
			const copyAllButton = document.querySelector('.js-copy-order_items')

			if (copyAllButton) {
				copyAllButton.addEventListener('click', async function() {
					// Get the HTML content of the entire document
					let text = '<style>* {line-height: 1 !important;}</style>';

					text += '------ DESIGN ------<br>'
					text += document.querySelector('#designs').innerHTML

					text += '<br>------ PRODOTTI ------<br>'
					text += document.querySelector('#products').innerHTML

					copyRichTextToClipboard(text)
				})

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
			}
		})
	</script>
@endpushOnce

<x-filament-panels::page>
	<div class="grid gap-8">
		{{-- Recap form --}}
		<x-dashboard.form.panel title="{{ ($ordersCount ? $orderItemsCount : 0) . ' Articoli da fare' }}">
			<form>
				@csrf

				<div class="grid w-full grid-cols-1 gap-8">
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

					@if ($orderItemsCount)
						<div class="flex h-10 gap-2">
							<div class="grid h-full">

								<x-button
									type="submit"
									value="clean_log"
									size="auto"
									color="danger"
									formaction="{{ route('admin.products-log.clean') }}"
								>
									<x-heroicon-o-trash />
									Pulisci log
								</x-button>
							</div>

							<x-button
								class="js-copy-order_items"
								size="auto"
							>
								<x-heroicon-o-clipboard-document-list class="text-white" />
								Copia tutto
							</x-button>
						</div>
					@endif

					<x-form.group>
						<div>
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

		{{-- Logs --}}
		<div class="grid gap-8 lg:grid-cols-2">
			{{-- Designs --}}
			<x-dashboard.form.panel
				title="Design"
				id="designs"
				class="log gap-8"
			>

				@if ($ordersCount)
					@php
						$sortedBySellers = $filteredOrderItems->sortBy('seller_id')->unique('seller_id');
					@endphp

					@foreach ($sortedBySellers as $sellerOrder)
						{{-- Sellers --}}
						<div class="seller">
							<h3 class="text-primary js-to-copy">[ {{ strtoupper($sellerOrder->storeName) }} ]</h3>

							@php
								// Get the order_items
								$sortedByProducts = $filteredOrderItems
								    ->where('seller_id', '=', $sellerOrder->seller_id)
								    ->sortBy('design_id')
								    ->unique('design_id');
							@endphp

							<div class="designs grid gap-4">
								@foreach ($sortedByProducts as $product)
									{{-- Designs --}}
									<div class="designs">
										<p class="js-to-copy text-neutral-700">
											{{ ($product->design_id ? $product->design_id . ' - ' : '') . $product->productName }}
										</p>

										@php
											$mergedorder_items = [];
											// Get the order_items
											$order_items = $filteredOrderItems
											    ->where('design_id', '=', $product->design_id)
											    ->sortBy('color_id')
											    ->sortBy('size_id')
											    ->sortBy('type_id');
										@endphp

										@foreach ($order_items as $orderItem)
											@php
												$orderItemDescription = $orderItem->description();
												$orderItemDescription = $orderItem->inventory
												    ? $orderItemDescription . ' <strong>(INVENTARIO)</strong>'
												    : $orderItemDescription;

												if (isset($mergedorder_items[$orderItemDescription])) {
												    $mergedorder_items[$orderItemDescription]['quantity'] += $orderItem->quantity;
												} else {
												    $mergedorder_items[$orderItemDescription] = [
												        'url' => $orderItem->product_with_trashed->fullUrl($orderItem->color_id, $orderItem->position_id),
												        'quantity' => 1,
												    ];
												}
											@endphp
										@endforeach

										@foreach ($mergedorder_items as $orderItemDescription => $productData)
											<a
												href="{{ $productData['url'] }}"
												target="_bank"
												class="js-to-copy"
											>
												<p>{!! $productData['quantity'] . ' x ' . $orderItemDescription !!}</p>
											</a>
										@endforeach
									</div>
								@endforeach
							</div>
						</div>
					@endforeach
				@else
					<p class="py-12">Nessun design da stampare</p>
				@endif
			</x-dashboard.form.panel>

			{{-- Products --}}
			<x-dashboard.form.panel
				title="Prodotti"
				id="products"
				class="log gap-8"
			>

				@if ($ordersCount)
					@php
						$sortedByTypes = $filteredOrderItems->sortBy('type_id')->unique('type_id');
					@endphp

					@foreach ($sortedByTypes as $typeOrder)
						{{-- Product type --}}
						<div class="product-type">
							<h3 class="text-primary js-to-copy">[ {{ strtoupper($typeOrder->productTypeName) }} ]</h3>

							@php
								$mergedorder_items = [];

								// Get the order_items
								$sortedByData = $filteredOrderItems
								    ->where('inventory', 0)
								    ->where('type_id', '=', $typeOrder->type_id)
								    ->sortBy('size_id')
								    ->sortBy('color_id');
							@endphp

							@foreach ($sortedByData as $orderItem)
								{{-- Order --}}
								@php
									$orderItemDescription = [];
									$orderItemDescription[] =
									    $orderItem->color && $orderItem->product_with_trashed->type->colors->count() > 1
									        ? $orderItem->color->name
									        : null;
									$orderItemDescription[] =
									    $orderItem->size && $orderItem->product_with_trashed->type->sizes->count() > 1
									        ? $orderItem->size->name
									        : null;
									$orderItemDescription = implode(' ', array_filter($orderItemDescription)) ?: 'Prodotto standard';

									if (isset($mergedorder_items[$orderItemDescription])) {
									    $mergedorder_items[$orderItemDescription] += $orderItem->quantity;
									} else {
									    $mergedorder_items[$orderItemDescription] = 1;
									}
								@endphp
							@endforeach

							@foreach ($mergedorder_items as $orderItemDescription => $quantity)
								<p class="js-to-copy">{{ $quantity . ' x ' . $orderItemDescription }}</p>
							@endforeach
						</div>
					@endforeach
				@else
					<p class="py-12">Nessun prodotto da stampare</p>
				@endif

			</x-dashboard.form.panel>
		</div>
	</div>
</x-filament-panels::page>
