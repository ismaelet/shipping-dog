@pushOnce('styles')
	<style>
		.log {
			font-family: Courier New;

			h3 {
				font-family: inherit;
			}
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

<x-layouts.admin-dashboard>
	<div class="grid gap-12">
		{{-- Title --}}
		<x-dashboard.typography.main-heading>Log dei prodotti</x-dashboard.typography.main-heading>

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
						<x-inputs.button
							type="submit"
							variant="secondary"
							size="auto"
						>
							<?= embed_icon('correct') ?>
							Applica
						</x-inputs.button>
					</div>
				</form>

				@if ($orderItemsCount)
					<form
						action=""
						method="POST"
						class="grid h-full"
					>
						<x-inputs.button
							type="submit"
							name="submit"
							value="clean_log"
							variant="secondary"
							size="auto"
						>
							<?= embed_icon('clean') ?>
							Pulisci log
						</x-inputs.button>
					</form>

					<x-inputs.button
						class="js-copy-order_items"
						size="auto"
					>
						<?= embed_icon('copy') ?>Copia tutto
					</x-inputs.button>
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
											    ->sortBy('size_id')
											    ->sortBy('color_id')
											    ->sortBy('type_id');
										@endphp

										@foreach ($order_items as $orderItem)
											@php
												$orderItemDescription = $orderItem->description();
												$orderItemDescription = $orderItem->inventory
												    ? $orderItemDescription . ' <strong>(INVENTARIO)</strong>'
												    : $orderItemDescription;

												if (isset($mergedorder_items[$orderItemDescription])) {
												    $mergedorder_items[$orderItemDescription] += $orderItem->quantity;
												} else {
												    $mergedorder_items[$orderItemDescription] = 1;
												}
											@endphp
										@endforeach

										@foreach ($mergedorder_items as $orderItemDescription => $quantity)
											<a
												href="{{ $orderItem->product->fullUrl($orderItem->color_id, $orderItem->position_id) }}"
												target="_bank"
												class="js-to-copy"
											>
												<p>{!! $quantity . ' x ' . $orderItemDescription !!}</p>
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
								    ->sortBy('color_id')
								    ->sortBy('size_id');
							@endphp

							@foreach ($sortedByData as $orderItem)
								{{-- Order --}}
								@php
									$orderItemDescription = [];
									$orderItemDescription[] =
									    $orderItem->size && $orderItem->product->type->sizes->count() > 1 ? $orderItem->size->name : null;
									$orderItemDescription[] =
									    $orderItem->color && $orderItem->product->type->colors->count() > 1 ? $orderItem->color->name : null;
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
</x-layouts.admin-dashboard>
