@use('App\Helpers\EcommerceHelper')
@use('App\Helpers\PreviewHelper')

@pushOnce('styles')
	<style>
		.status-bar {
			h3 {
				color: inherit;
			}
		}

		.status-icon {
			--icon-size: 50%;
			border-color: currentColor;
		}

		.page-panel {
			display: grid;
			gap: 1rem;
			padding: 1.5rem;
		}
	</style>
@endpushOnce


<x-layouts.main>

	<div class="bg-surface-500 full-width justify-items-center py-20 text-center">
		<x-typography.section-heading>
			Ordine <span class="text-primary"><?= $order->idCode() ?></span>
		</x-typography.section-heading>
	</div>

	<div class="mb-20 mt-12 grid gap-12">
		<div
			class="status-bar flex items-center justify-center gap-4"
			style="color: {{ $order->statusColor() }}"
		>
			<span class="status-icon aspect-square w-12 rounded-full border">
				{!! embed_icon($order->statusIcon()) !!}
			</span>

			<x-typography.component-heading>
				<?= $order->statusName() ?>
			</x-typography.component-heading>
		</div>

		@if (in_array($order->status, [EcommerceHelper::statusFromTag('received')]))
			<div class="grid justify-items-center gap-6">
				<p class="text-center">
					Siamo in attesa della conferma del pagamento. Se hai già pagato, per favore contattaci a <span
						class="font-bold text-neutral-700"
					><a href="{{ config('custom.social_media.email.url') }}">{{ config('custom.social_media.email.label') }}</a></span>
					e
					provvederemo a sbloccare l'ordine.<br><br>Se invece non hai ancora completato il pagamento, puoi farlo qui:
				</p>

				<x-inputs.button href="{{ $order->paymentUrl() }}">
					{!! embed_icon('credit-card') !!}
					Procedi al pagamento
				</x-inputs.button>
			</div>
		@elseif (in_array($order->status, [
				EcommerceHelper::statusFromTag('confirmed'),
				EcommerceHelper::statusFromTag('label-ready'),
				EcommerceHelper::statusFromTag('confirmed'),
				EcommerceHelper::statusFromTag('shipped'),
			]))
			<p>
				Il tuo ordine verrà spedito entro <span class="font-bold text-neutral-700">1-5 giorni lavorativi</span> dal momento
				del pagamento.
				Riceverai una
				email dal <span class="font-bold text-neutral-700">corriere GLS</span> con codice e istruzioni di <span
					class="font-bold text-neutral-700"
				>tracciamento</span> intorno alle
				<span class="font-bold text-neutral-700">20:00</span> del giorno di spedizione. Abbiamo mandato una email di
				conferma
				al tuo indirizzo
				email. Nel
				caso non appaia entro 5 minuti dal momento del pagamento, consigliamo di controllare la <span
					class="font-bold text-neutral-700"
				>cartella di
					spam</span>.
			</p>
		@endif

		<div class="grid items-start gap-6 sm:gap-8 lg:grid-cols-2 lg:gap-12">
			<div class="grid gap-6 sm:gap-8">
				<article class="page-panel surface">
					<x-typography.component-subheading>
						Metodo di pagamento
					</x-typography.component-subheading>

					<x-typography.component-heading>{{ $order->payment_method?->name }}</x-typography.component-heading>
				</article>

				<article class="page-panel surface">
					<x-typography.component-subheading>Metodo di spedizione</x-typography.component-subheading>

					<x-typography.component-heading>
						{!! $order->shipping_method->name !!}
					</x-typography.component-heading>

					<div>
						<p>
							{!! $order->shipping_method->deliveryDateString($order->created_at, $order->hasLongDelivery()) !!}
						</p>
						<p class="text-sm">{{ $order->shipping_method->desc }}</p>
					</div>
				</article>

				{{-- Address --}}
				<article class="page-panel surface">
					<x-typography.component-heading>Indirizzo</x-typography.component-heading>

					<p>
						<?= $order->address?->fullAddress() ?? '' ?><br>
						-<br>
						<?= $order->address?->email ?? '' ?><br>
						<?= $order->address?->phone ?? '' ?>
					</p>

					@if (in_array($order->status, [EcommerceHelper::statusFromTag('received'), EcommerceHelper::statusFromTag('confirmed')]))
						<p class="text-neutral-700">
							<a href="<?= client_url('/orders/' . $order->id . '/address?token=' . $order->token) ?>">
								Modifica l'indirizzo
							</a>
						</p>
					@endif

				</article>
			</div>

			<div class="grid gap-6 sm:gap-8">
				<article class="page-panel surface">
					<x-typography.component-heading>Articoli</x-typography.component-heading>

					@foreach ($order->order_items as $orderItem)
						<div class="grid grid-cols-2 items-center gap-4 md:grid-cols-4">
							<!--Image-->
							<?php if ($orderItem->product) { ?>
							<a
								href="<?= $orderItem->product ? $orderItem->product->url() : '' ?>"
								class="img_cont"
							>
								<img
									src="<?= $orderItem->image() ?>"
									class="rounded"
								>
							</a>
							<?php } else { ?>
							<div>
								<img
									src="<?= public_asset(PreviewHelper::PLACEHOLDER_IMAGE) ?>"
									class="rounded"
								>
							</div>
							<?php } ?>

							<!--Data-->
							<div class="md:col-span-3">
								<a href="<?= $orderItem->product ? $orderItem->product->url() : null ?>">
									<h3 class="art_name"><?= $orderItem->name() ?></h3>
								</a>

								<p class="font-semibold">
									<?= $orderItem->discount() ? '<span class="price-old">' . $orderItem->grossPriceString() . '</span>' : $orderItem->priceString() ?>
								</p>

								<?php if ($orderItem->discount()) { ?>

								<p class="price-promo font-semibold"><?= $orderItem->priceString() ?></p>

								<?php } ?>

								<p class="order-list__description">
									<?= $orderItem->multilineDescription() ?>
								</p>
							</div>
						</div>
					@endforeach

				</article>

				<article class="page-panel surface">
					<x-typography.component-heading>Riepilogo</x-typography.component-heading>

					<div>
						<div class="flex justify-between gap-4 py-3">
							<p class="">Articoli</p>
							<p class="font-medium text-neutral-700"><?= $order->grossOrderItemsPriceString() ?></p>
						</div>

						<div class="flex justify-between gap-4 border-t py-3">
							<p class="">Spedizione</p>
							<p class="font-medium text-neutral-700"><?= $order->shippingFeeString() ?></p>
						</div>

						@if ($order->orderItemsDiscount())
							<div class="flex justify-between gap-4 border-t py-3">
								<p class="">Sconto</p>
								<p class="font-medium text-neutral-700">- <?= $order->orderItemsDiscountString() ?></p>
							</div>
						@endif

						<div class="flex justify-between gap-4 border-t py-3 text-lg">
							<p class="">Totale</p>
							<p class="font-medium text-neutral-700"><?= $order->priceString() ?></p>
						</div>
					</div>
				</article>
			</div>
		</div>
	</div>

</x-layouts.main>
