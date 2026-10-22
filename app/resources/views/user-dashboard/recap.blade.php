<x-user-dashboard :$title>
	@php
		$stats = [
		    [
		        'label' => 'Ordini effettuati',
		        'value' => $orderCount,
		        'icon' => 'orders',
		        'iconClass' => 'bg-primary-400/10 text-primary-400',
		    ],
		    [
		        'label' => 'Prodotti nella wishlist',
		        'value' => $wishlistCount,
		        'icon' => 'heart',
		        'iconClass' => 'bg-secondary-400/10 text-secondary-400',
		    ],
		    [
		        'label' => 'Shop seguiti',
		        'value' => $followedShopCount,
		        'icon' => 'shop',
		        'iconClass' => 'bg-sky-400/10 text-sky-400',
		    ],
		];
	@endphp

	<div class="grid grid-cols-1 gap-4">
		<section
			aria-label="Statistiche account"
			class="grid grid-cols-1 gap-3 sm:grid-cols-3"
		>
			@foreach ($stats as $stat)
				<article class="border-neutral-border bg-surface-400 rounded-xl border p-5">
					<div class="flex items-center justify-between">
						<span class="{{ $stat['iconClass'] }} grid size-10 place-items-center rounded-full p-2.5">
							<x-dynamic-component :component="'icon-' . $stat['icon']" />
						</span>
						<span class="font-heading text-neutral-heading text-2xl font-bold leading-none">
							{{ str_pad((string) $stat['value'], 2, '0', STR_PAD_LEFT) }}
						</span>
					</div>
					<p class="text-neutral-text mt-4 text-sm">{{ $stat['label'] }}</p>
				</article>
			@endforeach
		</section>

		<section class="surface border-neutral-border bg-surface-400 rounded-2xl border p-5 sm:p-8">
			<x-user-dashboard.panel-header
				title="La tua attività"
				subtitle="Bentornato"
				text="Tutto quello che è successo nel tuo mondo ShippingDOG, in un solo posto."
			/>

			<div class="mt-6 grid grid-cols-1 gap-3">
				@forelse ($feedEntries as $feedEntry)
					<x-user-dashboard.feed-entry :entry="$feedEntry" />
				@empty
					<p class="text-neutral-text py-10 text-center text-sm">
						Non ci sono ancora attività da mostrare.
					</p>
				@endforelse
			</div>
		</section>
	</div>
</x-user-dashboard>
