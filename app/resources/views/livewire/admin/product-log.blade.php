@script
	<script>
		$wire.on('copyAll', async () => {
			// Helper to escape text before inserting into HTML
			const escapeHtml = (s) =>
				s?.replace(/[&<>"']/g, m => ({
					'&': '&amp;',
					'<': '&lt;',
					'>': '&gt;',
					'"': '&quot;',
					"'": '&#39;'
				} [m])) ?? '';

			const sections = [];
			const plainSections = [];

			// Add seller text
			const sellers = document.querySelectorAll('.js-seller');

			sellers.forEach(sellerEl => {
				const titleEl = sellerEl.querySelector('.js-title');
				const title = titleEl ? titleEl.textContent.trim() : '';

				const lines = Array.from(sellerEl.querySelectorAll('.js-to-copy'))
					.map(el => el.innerText.trim())
					.filter(Boolean);

				if (!title && !lines.length) return;

				// Build HTML section (inline styles → safer for Outlook/Word)
				const html =
					`<div style="margin:0 0 8px 0;">
		  ${title ? `<div style="color:#ff9600;font-weight:700;margin:0 0 4px 0;">${escapeHtml(title)}</div>` : ''}
		  <div>${lines.map(l => `${escapeHtml(l)}`).join('<br>')}</div>
		</div>`;

				// Build plain-text section
				const plain =
					`${title ? title + '\n' : ''}${lines.join('\n')}`;

				sections.push(html);
				plainSections.push(plain);
			});

			// Add separator between sellers and products
			sections.push('<div style="margin:8px 0; text-align:center;">---</div>');
			plainSections.push('---');

			// Add products text
			const productsEl = document.querySelector('.js-products');

			if (productsEl) {
				const productTypes = productsEl.querySelectorAll('.js-product');

				productTypes.forEach(productEl => {
					const titleEl = productEl.querySelector('.js-title');
					const title = titleEl ? titleEl.textContent.trim() : '';

					const lines = Array.from(productEl.querySelectorAll('.js-to-copy'))
						.map(el => el.innerText.trim())
						.filter(Boolean);

					if (!title && !lines.length) return;

					// Build HTML section (inline styles → safer for Outlook/Word)
					const html =
						`<div style="margin:0 0 8px 0;">
			${title ? `<div style="color:#ff9600;font-weight:700;margin:0 0 4px 0;">${escapeHtml(title)}</div>` : ''}
			<div>${lines.map(l => `${escapeHtml(l)}`).join('<br>')}</div>
		  </div>`;

					// Build plain-text section
					const plain =
						`${title ? title + '\n' : ''}${lines.join('\n')}`;

					sections.push(html);
					plainSections.push(plain);
				});
			}

			const htmlPayload = sections.join('<div style="height:8px"></div>');
			const plainPayload = plainSections.join('\n\n');

			try {
				// Copy as rich HTML + plain text
				await navigator.clipboard.write([
					new ClipboardItem({
						'text/html': new Blob([htmlPayload], {
							type: 'text/html'
						}),
						'text/plain': new Blob([plainPayload], {
							type: 'text/plain'
						}),
					}),
				]);

				console.log('Contenuto HTML copiato con successo!');
			} catch (err) {
				console.error('Errore nella copia HTML: ', err);
			}
		});
	</script>
@endscript

@pushOnce('styles')
	<style>
		.dashboard-panel {
			display: grid;
			align-content: start;
			gap: 1rem;
			border-radius: var(--radius-xl);
			background-color: white;
			padding: 1rem;
			box-shadow: var(--tw-inset-shadow), var(--tw-inset-ring-shadow), var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow);
			border: 1px solid;
			border-color: color-mix(in oklab, var(--color-white) 10%, transparent);

			.dark & {
				background-color: var(--gray-900);
			}
		}

		.dashboard-panel-title {
			padding-bottom: 0.5rem;
			padding-left: var(--radius-xl);
			font-size: 1.25rem;
			font-weight: 600;
		}

		.grid {
			display: grid;
		}

		.block {
			break-inside: avoid;
			display: block;

			&:first-child {
				margin-top: 0;
			}
		}

		.grid-cols-1 {
			grid-template-columns: repeat(1, minmax(0, 1fr));
		}

		.columns-15rem {
			grid-template-columns: repeat(auto-fill, minmax(min(15rem, 100%), 1fr));
		}

		.columns-20rem {
			grid-template-columns: repeat(auto-fill, minmax(min(20rem, 100%), 1fr));
		}

		.columns-25rem {
			grid-template-columns: repeat(auto-fill, minmax(min(25rem, 100%), 1fr));
		}

		.columns-3 {
			columns: 3;
		}

		.gap-8 {
			gap: 2rem;
		}

		.gap-4 {
			gap: 1rem;
		}

		.opacity-50 {
			opacity: 0.5;
		}

		.opacity-20 {
			opacity: 0.2;
		}

		.mt-2 {
			margin-top: 0.5rem;
		}

		.mt-4 {
			margin-top: 1rem;
		}

		.pointer-events-none {
			pointer-events: none;
		}

		.text-primary {
			color: var(--primary-400);
		}
	</style>
@endpushOnce

<div class="grid gap-8">

	{{-- Filter --}}
	<livewire:admin.product-log.filter
		:$orderItems
		:$firstOrder
	/>

	{{-- Logs --}}
	<div class="grid gap-8 lg:grid-cols-2">
		{{-- Designs --}}
		<div class="">
			<h3 class="dashboard-panel-title">Design</h3>
			<div class="dashboard-panel">

				@if ($orderItems?->count())
					@php
						$sortedBySellers = $orderItems->sortBy('seller_id')->unique('seller_id');
					@endphp

					<div class="columns-3">
						@foreach ($sortedBySellers as $sellerOrder)
							{{-- Sellers --}}
							<div class="seller js-seller mt-4 block">
								<h3 class="text-primary js-title">{{ strtoupper($sellerOrder->storeName) }}</h3>

								@php
									// Get the order_items
									$sortedByProducts = $orderItems
									    ->where('seller_id', '=', $sellerOrder->seller_id)
									    ->sortBy('design_id')
									    ->unique('design_id');
								@endphp

								<div class="designs grid gap-4">
									@foreach ($sortedByProducts as $product)
										<div class="design js-design">
											<p class="js-title text-neutral-700">
												[ {{ ($product->design_id ? $product->design_id . ' - ' : '') . $product->productName }} ]
											</p>

											@php
												$mergedorder_items = [];
												// Get the order_items
												$order_items = $orderItems
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
												<p class="js-to-copy">
													<a
														href="{{ $productData['url'] }}"
														target="_bank"
													>{!! $productData['quantity'] . ' x ' . $orderItemDescription !!}</a>
												</p>
											@endforeach
										</div>
									@endforeach
								</div>
							</div>
						@endforeach
					</div>
				@else
					<p class="py-12">Nessun design da stampare</p>
				@endif
			</div>
		</div>

		{{-- Products --}}
		<div class="js-products">
			<h3 class="dashboard-panel-title">Prodotti</h3>
			<div class="dashboard-panel">

				@if ($orderItems?->count())
					@php
						$sortedByTypes = $orderItems->sortBy('type_id')->unique('type_id');
					@endphp
					<div class="columns-3">

						@foreach ($sortedByTypes as $typeOrder)
							{{-- Product type --}}
							<div class="product-type js-product mt-4 block">
								<h3 class="text-primary js-title">{{ strtoupper($typeOrder->productTypeName) }}</h3>

								@php
									$mergedorder_items = [];

									// Get the order_items
									$sortedByData = $orderItems
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
					</div>
				@else
					<p class="py-12">Nessun prodotto da stampare</p>
				@endif

			</div>
		</div>
	</div>
</div>
