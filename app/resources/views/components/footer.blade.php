@php
	$footer = [
	    'columns' => [
	        [
	            'title' => 'Esplora',
	            'links' => [
	                ['label' => 'Home', 'url' => route('home')],
	                ['label' => 'Area riservata', 'url' => route('user-dashboard')],
	                ['label' => 'Shop', 'url' => route('shop')],
	                ['label' => 'Vendi', 'url' => route('open-shop')],
	            ],
	        ],
	        [
	            'title' => 'Assistenza',
	            'links' => [
	                ['label' => 'Contatti', 'url' => route('contacts')],
	                ['label' => 'Cambio taglia', 'url' => route('support.refund.search')],
	                ['label' => 'Stato ordine', 'url' => route('orders.find')],
	                ['label' => 'FAQ', 'url' => route('support.faq')],
	            ],
	        ],
	        [
	            'title' => 'Legali',
	            'links' => [
	                ['label' => 'Condizioni di vendita', 'url' => route('support.sale-terms')],
	                ['label' => 'Condizioni di invio', 'url' => route('support.upload-terms')],
	                ['label' => 'Privacy policy', 'url' => route('support.privacy-policy')],
	                ['label' => 'P. IVA ' . config('ecommerce.vat')],
	            ],
	        ],
	    ],
	];
@endphp

<footer class="bg-slate-950 px-5 py-14 text-white">
	<div class="mx-auto grid max-w-7xl gap-12 sm:grid-cols-2 lg:grid-cols-[2fr_1fr_1fr_1fr] lg:px-8">
		{{-- Brand --}}
		<div>
			<a
				href="{{ route('home') }}"
				class="inline-block text-2xl font-black tracking-[-0.04em]"
				aria-label="Shipping Dog - Home"
			>
				SHIPPING<span class="text-orange-500">DOG</span>
			</a>

			<p class="mt-4 max-w-sm leading-6 text-slate-400">
				Design indipendente, produzione responsabile,
				stile senza regole.
			</p>

			{{-- Social contacts --}}
			<div class="mt-6 flex flex-wrap items-center gap-3">
				@foreach (config('custom.contacts') as $socialMediaTag => $socialMedia)
					<a
						href="{{ $socialMedia['url'] }}"
						class="grid size-10 place-items-center rounded-full border border-white/10 bg-white/5 text-slate-300 transition-colors hover:border-orange-500 hover:bg-orange-500 hover:text-white [&_svg]:size-5"
						aria-label="{{ ucfirst($socialMediaTag) }}"
						title="{{ ucfirst($socialMediaTag) }}"
					>
						{!! embed_icon($socialMediaTag) !!}
					</a>
				@endforeach
			</div>
		</div>

		{{-- Footer links --}}
		@foreach ($footer['columns'] as $column)
			<div>
				<h3 class="text-lg font-bold text-white">
					{{ $column['title'] }}
				</h3>

				<ul class="mt-4 flex flex-col gap-3 text-slate-400">
					@foreach ($column['links'] as $link)
						<li>
							@if (isset($link['url']))
								<a
									href="{{ $link['url'] }}"
									class="transition-colors hover:text-orange-400"
								>
									{{ $link['label'] }}
								</a>
							@else
								<span>
									{{ $link['label'] }}
								</span>
							@endif
						</li>
					@endforeach
				</ul>
			</div>
		@endforeach
	</div>


	{{-- Bottom notes --}}
	<div
		class="mx-auto mt-12 flex max-w-7xl flex-col items-center justify-between gap-6 border-t border-white/10 pt-6 text-sm leading-5 text-slate-500 sm:flex-row lg:px-8"
	>
		<div class="text-center sm:text-left">
			<p>
				© {{ date('Y') }}
				{{ config('custom.meta_data.full_name') }}.
				Tutti i diritti riservati.
			</p>

			<p class="mt-1">
				Il copyright © di ogni grafica appartiene al rispettivo proprietario.
			</p>
		</div>

		<x-theme-switcher />
	</div>
</footer>
