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
			const searchInput = document.querySelector('[name="start_cart_id"]')
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
			 * Copy the orders content
			 */
			const copyAllButton = document.querySelector('.js-copy-orders');

			// Copy from cart set in the header
			if (copyAllButton) {
				copyAllButton.addEventListener('click', async function() {
					cartId = document.querySelector('[name="start_copy_cart_id"]').value

					copyOrdersContent(cartId)
				})
			}

			// Copy from specific cart
			document.querySelectorAll('.js-copy-from-cart').forEach((button) => {
				button.addEventListener('click', async function() {
					cartId = this.getAttribute('data-id')

					copyOrdersContent(cartId)
				})
			})

			// Copy the orders content
			function copyOrdersContent(startingCartId) {
				let orders = []
				let order = document.querySelector(`.js-order[data-id="${startingCartId}"]`);

				orders.push(order);

				while (nextOrder = selectNextOrder(order)) {
					order = nextOrder;

					if (order) {
						orders.push(order);
					}
				}

				// Get the HTML content of the entire document
				let text = '';

				orders.forEach((order) => {
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
		<x-dashboard.form.panel :title="'Mostrando ' . $carts->count() . ' ordini'">
			<div class="grid gap-8">

				@if ($carts->first())
					<x-form.group>
						<x-inputs.label>Copia a partire da:</x-inputs.label>
						<div class="flex gap-4">
							<x-inputs.search-input
								search="carts"
								stored-name="start_copy_cart_id"
								stored-value="{{ $carts->first()->id }}"
								shown-value="{{ $carts->first()->id . ' - ' . $carts->first()->address->full_name }}"
								placeholder="Ordine da cui copiare"
								:where="$cartsFilter"
								required
								class="grow"
							/>

							<x-inputs.button
								class="js-copy-orders"
								size="auto"
							>
								{!! embed_icon('copy') !!}
								Copia il log
							</x-inputs.button>
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
								search="carts"
								stored-name="start_cart_id"
								stored-value="{{ $carts->first()->id ?? '' }}"
								shown-value="{{ $carts->first() ? $carts->first()->id . ' - ' . $carts->first()->address->full_name : '' }}"
								placeholder="Ordine da cui partire"
								required
								class="grow"
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

						@if ($carts->count())
							<form
								action=""
								method="POST"
								class="contents"
							>
								<x-inputs.button
									type="submit"
									name="submit"
									value="clean_log"
									variant="secondary"
									class=""
									size="auto"
								>
									{!! embed_icon('delete') !!}
									Pulisci il log
								</x-inputs.button>
							</form>
						@endif
					</div>
				</x-form.group>
			</div>
		</x-dashboard.form.panel>

		{{-- Carts --}}
		@if ($carts->count())
			@foreach ($carts as $cart)
				@php
					$title = $cart->idCode() . ' ' . ($cart->address->full_name ?? '');
					$title = $cart->user && $cart->user->isSeller() ? $title . ' - <span class="text-error">Venditore</span>' : $title;
				@endphp

				<x-dashboard.form.panel
					:title="$title"
					id="carts"
					class="order log js-order"
					data-id="{{ $cart->id }}"
				>

					{{-- Order header --}}
					<p class="js-to-copy">[{{ $cart->id }} - {{ $cart->createdAtString() }}]</p>

					{{-- Address --}}
					@if ($cart->address)
						{{-- If found --}}
						<div>
							<p class="js-to-copy">{!! $cart->address->fullAddress() !!}</p>
							<p>---</p>
							<p>{{ $cart->address->email }}</p>
							<p>{{ $cart->address->phone }}</p>
						</div>
					@else
						{{-- If error --}}
						@php
							$cart->has_errors = 1;
							$cart->save();

							// Get the PayPal data
							/*$paypalData = paypal_find_order($cart->id);

							if ($paypalData) {
							// Create the address
							$addressLine1 = explode(' ', $paypalData->shipping_info->address->line1);
							$addressNumber = array_pop($addressLine1);
							$addressLine1 = implode(' ', $addressLine1);

							Address::create([
							'cart_id' => $cart->id,
							'user_id' => $reg ? $cart->user_id : null,
							'full_name' => $paypalData->shipping_info->name,
							'address' => $addressLine1,
							'number' => $addressNumber,
							'city' => $paypalData->shipping_info->address->city,
							'zip' => $paypalData->shipping_info->address->postal_code,
							'country' => $paypalData->shipping_info->address->country_code,
							'email' => $paypalData->payer_info->email_address,
							'phone' => $paypalData->payer_info->phone_number->national_number,
							]);*/

						@endphp

						<p class="text-error">!!! Errore nell'indirizzo !!!</p>
					@endif

					{{-- Coupon --}}
					@if ($cart->coupon)
						<p>Sconto: <span class="font-bold">{{ $cart->code }}</span></p>
					@endif

					{{-- Orders --}}
					<div class="js-to-copy">
						@foreach ($cart->orders as $order)
							<p>
								@if ($order->inventory == 1)
									<span class="text-green font-bold">! IN INVENTARIO !</span>
								@endif

								@if ($order->inventory == 2)
									<span class="text-dark-green font-bold">! SIMILE IN INVENTARIO !</span>
								@endif

								<a
									href="{{ $order->product->fullUrl($order->color_id, $order->position_id) }}"
									target="_blank"
								>
									{{ $order->fullName() }}
								</a>
							</p>
						@endforeach
					</div>

					{{-- Shipping --}}
					<p class="js-to-copy">{!! $cart->shipping_method->name !!}, {!! $cart->shipping_method->deliveryDateString($cart->created_at, $cart->hasLongDelivery()) !!}</p>

					{{-- Price --}}
					@if (!$cart->change_request)
						<p class="js-to-copy">Totale: <strong>{!! $cart->priceString() !!}</strong></p>
					@else
						<p class="text-blue js-to-copy font-bold">CAMBIO TAGLIA</p>
					@endif

					<x-inputs.button
						class="js-copy-from-cart"
						data-id="{{ $cart->id }}"
						size="small"
						variant="secondary"
					>
						{!! embed_icon('copy') !!}
						Copia a partire da qui
					</x-inputs.button>
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
