@use('App\Helpers\EcommerceHelper')

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
			Ordine <span class="text-primary">{{ $order->idCode() }}</span>
		</x-typography.section-heading>
	</div>

	<div class="mb-20 mt-12 grid gap-12">
		<div
			class="status-bar flex items-center justify-center gap-4"
			style="color: {{ $order->statusColor() }}"
		>
			<span class="status-icon grid aspect-square w-12 place-items-center rounded-full border">
				<x-dynamic-component
					:component="'icon-' . $order->statusIcon()"
					class="w-1/2"
				/>
			</span>

			<x-heading.component>
				{{ $order->statusName() }}
			</x-heading.component>
		</div>

		@if (in_array($order->status, [EcommerceHelper::statusFromTag('received')]))
			<form
				class="grid justify-items-center gap-6"
				method="POST"
				action="{{ route('orders.destroy', [$order, 'token' => $order->token]) }}"
			>

				@csrf
				@method('DELETE')

				<p class="text-center">
					Siamo in attesa della conferma del pagamento. Se hai già pagato, per favore contattaci a <span
						class="text-neutral-heading font-bold"
					><a href="{{ config('custom.contacts.email.url') }}">{{ config('custom.contacts.email.label') }}</a></span>
					e
					provvederemo a sbloccare l'ordine.<br><br>Se invece non hai ancora completato il pagamento, puoi farlo qui, oppure
					puoi cancellare l'ordine se hai cambiato idea:
				</p>

				@session('error')
					<x-alerts.error>
						{{ session('error') }}
					</x-alerts.error>
				@endsession

				<div class="flex flex-wrap justify-center gap-4">
					<x-button
						href="{{ $order->paymentUrl() }}"
						class="grow sm:grow-0"
					>
						{!! embed_icon('credit-card') !!}
						Procedi al pagamento
					</x-button>

					<x-button
						type="submit"
						variant="secondary"
						color="error"
						class="grow sm:grow-0"
					>
						{!! embed_icon('close') !!}
						Cancella l'ordine
					</x-button>
				</div>
			</form>
		@elseif (in_array($order->status, [
				EcommerceHelper::statusFromTag('confirmed'),
				EcommerceHelper::statusFromTag('awaiting-shipping'),
				EcommerceHelper::statusFromTag('confirmed'),
				EcommerceHelper::statusFromTag('shipped'),
			]))
			<p>
				Il tuo ordine verrà spedito entro <span
					class="text-neutral-heading font-bold">{{ $order->shipping_method->minimumWorkingDays($order->hasLongDelivery()) . '-' . $order->shipping_method->maximumWorkingDays($order->hasLongDelivery()) }}
					giorni lavorativi</span> dal momento della conferma.
				Riceverai una
				email dal <span class="text-neutral-heading font-bold">corriere GLS</span> con codice e istruzioni di <span
					class="text-neutral-heading font-bold"
				>tracciamento</span> intorno alle
				<span class="text-neutral-heading font-bold">20:00</span> del giorno di spedizione.<br><br>Abbiamo mandato una email
				di
				conferma
				al tuo indirizzo
				email. Nel
				caso non appaia entro 5 minuti dal momento del pagamento, consigliamo di controllare la <span
					class="text-neutral-heading font-bold"
				>cartella di
					spam</span>.
			</p>
		@endif

		<div class="grid items-start gap-6 sm:gap-8 lg:grid-cols-2 lg:gap-12">
			<div class="grid gap-6 sm:gap-8">
				{{-- Payment --}}
				<article class="page-panel surface">
					<x-typography.component-subheading>
						Metodo di pagamento
					</x-typography.component-subheading>

					<x-heading.component>{{ $order->payment_method?->name }}</x-heading.component>
				</article>

				{{-- Shipping --}}
				<article class="page-panel surface">
					<x-typography.component-subheading>Metodo di spedizione</x-typography.component-subheading>

					<x-heading.component>
						{!! $order->shipping_method->name !!}
					</x-heading.component>

					<div>
						<p>
							{!! $order->shipping_method->deliveryDateString($order->created_at, $order->hasLongDelivery()) !!}
						</p>
						<p class="text-sm">{{ $order->shipping_method->desc }}</p>
					</div>
				</article>

				{{-- Address --}}
				<article class="page-panel surface">
					<x-heading.component>Indirizzo</x-heading.component>

					<p>
						{!! implode('<br>', $order->address->full_address ?? []) !!}<br>
						-<br>
						{!! $order->address?->email ?? '' !!}<br>
						{!! $order->address?->phone ?? '' !!}
					</p>
				</article>
			</div>

			<div class="grid gap-6 sm:gap-8">
				<section class="page-panel surface">
					<x-heading.component>Articoli</x-heading.component>

					<livewire:cart.order-items-list :order="$order" />
				</section>

				<section class="page-panel surface">
					<x-heading.component>Riepilogo</x-heading.component>

					<livewire:cart.recap-panel :order="$order" />
				</section>
			</div>
		</div>
	</div>

</x-layouts.main>
