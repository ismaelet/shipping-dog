@php

	use App\Helpers\EcommerceHelper;
	use App\Models\Ecommerce\Order;
	use App\Models\Ecommerce\OrderItem;
	use App\Models\Framework\Configuration;

	// Get the date from which to start the log
	$startFromTime = Configuration::where('name', 'product_log_start_from')->value('value');
	$validorders = Order::where('created_at', '>=', $startFromTime)->where(
	    'status',
	    '>',
	    EcommerceHelper::statusFromTag('received'),
	);
	$startFromCart = $validorders->exists() ? $validorders->oldest()->first() : null;

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
	        )
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

	$validordersCount = $validorders->count();
	$orderItemsCount = $filteredOrderItems?->count() ?? 0;
	$firstCart = $firstCart ?? null;
	$filteredOrderItems = $filteredOrderItems ?? null;

@endphp

@pushOnce('styles')
	<style>
		:root {
			--color-primary-400: oklch(82.8% 0.189 84.429);
			--color-neutral-400: oklch(70.8% 0 0);

			--border-color: var(--color-neutral-400);

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
		<x-dashboard.form.panel title="{{ ($validordersCount ? $orderItemsCount : 0) . ' Articoli da fare' }}">
			<x-inputs.label>Parti dall'ordine:</x-inputs.label>

			<div class="flex w-full items-center gap-4">
				<form
					action=""
					method="POST"
					class="contents"
				>
					<x-inputs.search-input
						class="grow"
						search="orders"
						stored-name="start_order_id"
						stored-value="{{ $startFromCart ? $startFromCart->id : null }}"
						shown-value="{{ $startFromCart ? $startFromCart->id . ' - ' . $startFromCart->address->full_name : '' }}"
						placeholder="Ordine da cui partire"
						required
					/>

					<div class="js-apply-search hidden">
						<x-button
							type="submit"
							variant="secondary"
							size="auto"
						>
							<?= embed_icon('correct') ?>
							Applica
						</x-button>
					</div>
				</form>

				@if ($orderItemsCount)
					<form
						action=""
						method="POST"
						class="grid h-full"
					>
						<x-button
							type="submit"
							name="submit"
							value="clean_log"
							variant="secondary"
							size="auto"
						>
							<?= embed_icon('clean') ?>
							Pulisci log
						</x-button>
					</form>

					<x-button
						class="js-copy-order_items"
						size="auto"
					>
						<?= embed_icon('copy') ?>Copia tutto
					</x-button>
				@endif
			</div>

		</x-dashboard.form.panel>

		{{-- Logs --}}
		<div class="grid gap-8 lg:grid-cols-2">
			{{-- Designs --}}
			<x-dashboard.form.panel
				title="Design"
				id="designs"
				class="log gap-8"
			>

				@if ($validordersCount)
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

				@if ($validordersCount)
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
