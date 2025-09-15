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

		.flex {
			display: flex;
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

		.items-center {
			align-items: center;
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

		.gap-2 {
			gap: 0.5rem;
		}

		.opacity-50 {
			opacity: 0.5;
		}

		.opacity-30 {
			opacity: 0.3;
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

		.w-6 {
			width: 1.5rem;
		}

		.h-6 {
			height: 1.5rem;
		}

		.w-4 {
			width: 1rem;
		}

		.h-4 {
			height: 1rem;
		}

		.w-2\/3 {
			width: 66.666667%;
		}

		.h-2\/3 {
			height: 66.666667%;
		}

		.w-1\/2 {
			width: 50%;
		}

		.h-1\/2 {
			height: 50%;
		}

		.border-2 {
			border-width: 2px;
		}

		.rounded-full {
			border-radius: 9999px;
		}

		.place-items-center {
			place-items: center;
		}

		.cursor-pointer {
			cursor: pointer;
		}

		.dashboard-panel svg {
			fill: currentColor;
		}

		.data-\[active\=true\]\:opacity-100[data-active="true"] {
			opacity: 1;
		}

		.data-\[active\=true\]\:text-secondary-400[data-active="true"] {
			color: var(--secondary-400);
		}

		.data-\[active\=true\]\:text-success-400[data-active="true"] {
			color: var(--success-400);
		}

		.transition-all {
			transition-property: all;
			transition-duration: 150ms;
			transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
		}

		.text-sm {
			font-size: 0.875rem;
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
					<div class="columns-3">
						{{-- Filter by seller --}}
						@foreach ($orderItems->pluck('seller')->unique() as $seller)
							<div class="seller js-seller mt-4 block">
								<h3 class="text-primary js-title">{{ strtoupper($seller->name) }}</h3>

								<div class="designs grid gap-4">
									{{-- Filter by design --}}
									@foreach ($orderItems->where('seller_id', $seller->id)->pluck('design')->unique() as $design)
										<div class="design js-design">
											<p class="js-title text-neutral-700">
												--- {{ ($design ? $design->id . ' - ' : '') . $design->name }} ---
											</p>

											<div class="mt-2 grid grid-cols-1 gap-2">
												{{-- Filter by type --}}
												@foreach ($orderItems->where('design_id', $design->id)->pluck('type')->unique() as $type)
													{{-- Filter by color --}}
													@foreach ($orderItems->where('design_id', $design->id)->where('type_id', $type->id)->sortBy('color_id')->pluck('color')->unique() as $color)
														{{-- Filter by size --}}
														@foreach ($orderItems->where('design_id', $design->id)->where('type_id', $type->id)->where('color_id', $color->id)->sortBy('size_id')->pluck('size')->unique() as $size)
															<livewire:admin.product-log.type-item
																:key="`type-item-{{ $type->id }}-color-{{ $color->id }}-size-{{ $size->id }}`"
																:order-items="$orderItems"
																:type="$type"
																:color="$color"
																:size="$size"
																:design="$design"
															/>
														@endforeach
													@endforeach
												@endforeach
											</div>
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
						$productTypes = $orderItems->sortBy('type_id')->pluck('type')->unique('id');
					@endphp

					<div class="columns-3">
						@foreach ($productTypes as $productType)
							<div class="product-type js-product mt-4 block">
								<h3 class="text-primary js-title">{{ strtoupper($productType->name) }}</h3>

								<div class="mt-2 grid grid-cols-1 gap-2">
									{{-- Filter by color --}}
									@foreach ($orderItems->where('type_id', $productType->id)->sortBy('color_id')->pluck('color')->unique() as $color)
										{{-- Filter by size --}}
										@foreach ($orderItems->where('type_id', $productType->id)->where('color_id', $color->id)->sortBy('size_id')->pluck('size')->unique() as $size)
											<livewire:admin.product-log.type-item
												:key="`type-item-{{ $productType->id }}-color-{{ $color->id }}-size-{{ $size->id }}`"
												:order-items="$orderItems"
												:type="$productType"
												:color="$color"
												:size="$size"
											/>
										@endforeach
									@endforeach
								</div>
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
