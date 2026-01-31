@php

	use App\Models\Ecommerce\Design;
	use App\Models\Ecommerce\Product;
	use Legacy\Classes\Ecommerce\Design as LegacyDesign;
	use Legacy\Classes\Ecommerce\Product as LegacyProduct;

@endphp

@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/portfolio-index.css') }}"
	>
@endpushOnce

@pushOnce('scripts')
	<script
		defer
		src="{{ asset('js/legacy/portfolio-index.js') }}"
	></script>
@endpushOnce

<x-layouts.seller-dashboard>
	<div class="grid grid-cols-1 gap-8">
		<livewire:seller.activation-request :$seller />
		<form
			method="POST"
			class="surface dashboard-panel"
		>
			@csrf
			<x-heading.component>Portfolio</x-heading.component>

			<div class="mt-12 grid grid-cols-1 gap-8 text-center">

				@if (!$totalCount)
					<p class="txt-c-title txt-med txt-ls05 mar-b-1">Ancora non hai caricato nessuna grafica. Clicca qui sotto per
						cominciare:</p>
				@endif

				<div class="flex flex-wrap justify-center gap-8">
					<x-button
						href="{{ route('seller-dashboard.portfolio.add') }}"
						class="button--auto-width"
					>
						{!! embed_icon('upload') !!}
						Carica una grafica
					</x-button>

					@if ($isCustomSeller)
						<x-button
							href="{{ route('seller-dashboard.portfolio.products.add') }}"
							class="button--auto-width"
							variant="secondary"
						>
							{!! embed_icon('upload') !!}
							Carica un prodotto
						</x-button>
					@endif

				</div>

				@if ($totalCount)

					<div
						id="sort-box"
						class="text-center"
					>
						<div class="border-surface-500 grid grid-cols-1 justify-items-center gap-4 border border-dashed p-4 sm:p-8">
							<div
								id="sort-icon"
								class="icon inlb"
							>{!! embed_icon('move') !!}</div>

							<p
								id="sort-txt"
								class="mar-h-auto"
							>
								Puoi riordinare i lavori a piacimento trascinandoli nella posizione desiderata.
								Se vuoi che i tuoi clienti vedano i prodotti in quest'ordine, vai nelle <a
									class="text-link"
									href="{{ route('seller-dashboard') }}"
								>impostazioni generali</a> e scegli <span class="font-semibold text-neutral-700">"ordine personalizzato"</span>
								sotto la voce <span class="font-semibold text-neutral-700">"ordina
									prodotti"</span>.
							</p>

							<x-button
								type="submit"
								id="sort-btn"
								name="sort"
								class="hidden"
								variant="secondary"
								formaction="{{ route('seller-dashboard.portfolio.sort') }}"
							>Applica</x-button>
						</div>
					</div>

					@if ($designs->count())
						<div
							id="gallery"
							class="mar-t-2 gap1 js-sortable grid text-center"
						>

							@foreach ($designs as $design)
								<div class="img-box">
									<input
										type="hidden"
										name="id[]"
										value="{{ $design->id }}"
									>
									<input
										type="hidden"
										class="pos js-order"
										name="pos[]"
										value="{{ $design->sort }}"
									>

									<a
										class="des-img sqr block"
										href="{{ route('designs.edit', $design) }}"
									>
										<img
											class="img-fill"
											src="{{ $design->image() }}"
											loading="lazy"
										>
									</a>

									<p class="name fa-center txt-c-title txt-med mar-t-1 txt-line">{{ $design->name }}</p>
								</div>
							@endforeach

						</div>

					@endif

					@if ($customProducts->count())
						<div
							class="grid-flow"
							data-spacing="700"
						>
							<x-heading.component-small class="">Prodotti
								personalizzati</x-heading.component-small>

							<div
								class="grid-flow js-sortable"
								data-spacing="400"
								data-columns="15rem"
							>

								@foreach ($customProducts as $product)
									<div
										class="img-box grid-flow fa-center"
										data-spacing="200"
									>
										<input
											type="hidden"
											name="product-id[]"
											value="{{ $product->id }}"
										>
										<input
											type="hidden"
											class="pos js-order"
											name="product-order[]"
											value="{{ $product->sort }}"
										>

										<a
											class="des-img sqr block"
											href="{{ route('seller-dashboard.portfolio.products.show', $product) }}"
										>
											<img
												class="img-fill"
												src="{{ $product->image() }}"
												loading="lazy"
											>
										</a>

										<div>
											<p class="fc-titles">{{ $product->name }}</p>
											<p class="">{{ $product->type->name }}</p>

											@if (!$product->status)
												<p class="fc-warning">Non pubblicato</p>
											@endif

										</div>
									</div>
								@endforeach

							</div>
						</div>
					@endif

				@endif
			</div>

		</form>

	</div>

	<script
		src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"
		integrity="sha256-lSjKY0/srUM9BE3dPm+c4fBo1dky2v27Gdjm2uoZaL0="
		crossorigin="anonymous"
	></script>

</x-layouts.seller-dashboard>
