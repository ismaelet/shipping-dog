@pushOnce('styles')
	<style>
		.log {
			font-family: Courier New;

			h3 {
				font-family: inherit;
			}

			strong {
				color: var(--color-neutral-700);
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
@endpushOnce

<x-layouts.admin-dashboard>

	<div class="grid gap-12">

		{{-- Title --}}
		<x-dashboard.typography.main-heading>Log degli ordini</x-dashboard.typography.main-heading>

		{{-- Recap form --}}
		<x-dashboard.form.panel :title="'Mostrando ' . $orders->count() . ' ordini'">
			<div class="grid gap-8">

				@if ($orders->first())
					<x-form.group>
						<x-inputs.label>Copia a partire da:</x-inputs.label>
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

							<x-button
								class="js-copy-order_items"
								size="auto"
							>
								{!! embed_icon('copy') !!}
								Copia il log
							</x-button>
						</div>
					</x-form.group>
				@endif

				<x-form.group>
					<x-inputs.label>Carica a partire da:</x-inputs.label>

					<div class="flex gap-4">
						<form
							action=""
							method="POST"
							class="contents"
						>
							<x-inputs.search-input
								search="orders"
								stored-name="start_order_id"
								stored-value="{{ $orders->first()->id ?? '' }}"
								shown-value="{{ $orders->first() ? $orders->first()->id . ' - ' . $orders->first()->address->full_name : '' }}"
								placeholder="Ordine da cui partire"
								required
								class="grow"
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

						@if ($orders->count())
							<form
								action=""
								method="POST"
								class="contents"
							>
								<x-button
									type="submit"
									name="submit"
									value="clean_log"
									variant="secondary"
									class=""
									size="auto"
								>
									{!! embed_icon('delete') !!}
									Pulisci il log
								</x-button>
							</form>
						@endif
					</div>
				</x-form.group>

				<x-form.group>
					<form
						method="GET"
						action=""
					>
						<x-checkbox
							name="show_all"
							text="Mostra tutti gli ordini"
							checked="{{ $showAll }}"
							class="js-submit-form"
						/>
					</form>
				</x-form.group>
			</div>
		</x-dashboard.form.panel>

		{{-- Orders --}}
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
					<p class="js-to-copy">[{{ $order->id }} - {{ $order->createdAtString() }} - {{ $order->payment_method->name }}]
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
						size="small"
						variant="secondary"
					>
						{!! embed_icon('copy') !!}
						Copia a partire da qui
					</x-button>
				</x-dashboard.form.panel>
			@endforeach
		@else
			<x-dashboard.form.panel>
				<p class="py-8">
					Nessun ordine registrato dopo le <span
						class="font-bold text-neutral-700"><?= (new DateTime($startFromTime))->format('H:i:s') ?></span>
					del <span class="font-bold text-neutral-700"><?= (new DateTime($startFromTime))->format('d/m/Y') ?></span>
				</p>
			</x-dashboard.form.panel>
		@endif
	</div>
</x-layouts.admin-dashboard>
