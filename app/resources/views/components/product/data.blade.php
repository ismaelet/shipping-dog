@use('App\Models\Ecommerce\Product\ProductSize')

@props(['product', 'productColors', 'warning'])

@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/product-data.css') }}"
	>
@endpushOnce

<div
	class="breakout pad-t-3 item-type--{{ $product->type->tag }} mb-8"
	id="item"
	data-id="{{ $product->id }}"
	data-hroot="/legacy"
	data-seller-id="{{ $product->seller->id }}"
>

	<form
		action="{{ route('order-items.store') }}"
		method="POST"
		class="content gap3 al-top grid"
	>
		@csrf

		<input
			type="hidden"
			name="product_id"
			value="{{ $product->id }}"
		>

		<livewire:product.images :$product />

		<section
			id="right-panel"
			class="grid"
		>
			<div class="">
				{{-- Title --}}
				<h1 id="title">{{ $product->name }}</h1>

				<p id="author">{{ $product->type->name }}
					by <a
						class="txt-c1-h"
						href="{{ $product->seller->url() }}"
					>
						{{ $product->seller->name }}
					</a>
				</p>

				{{-- Price --}}
				<div class="price-block">

					@if ($product->userIsOwner())

						{{-- Price when there's a discount --}}
						<p class="price price-old font-heading">{{ $product->fullPriceString() }}</p>
						<p class="price price-promo font-heading">{{ $product->sellerPriceString() }}</p>

						<div class="flex items-center gap-2">
							<p class="price-txt">Prezzo scontato{{ $product->userIsOwner() ? ' solo per te' : '' }}</p>

							@if ($product->userIsOwner())
								<x-help.info>Questo prezzo speciale è visibile a te, perché sei il proprietario di questo articolo. I clienti
									vedranno solo il prezzo normale di <span class=''>{{ $product->fullPriceString() }}</span>
								</x-help.info>
							@endif
						</div>
					@else
						{{-- Normal price --}}
						<p class="price price-def font-heading js-price font-semibold text-neutral-700">
							{{ $product->customerPriceString() }}</p>

					@endif

				</div>

				{{-- Colors --}}
				@if ($product->colors()->count())
					<livewire:product.options.color :$product />
				@endif

				{{-- Size --}}
				@if ($product->type->sizes->count())
					<x-product.options.size :$product />
				@endif

				{{-- Gender --}}
				@if ($product->type->gender)
					@php
						$new = true;

						$genders = [];
						$genders[] = $product->type->gender;
						$sibling = $product->type->sibling;

					@endphp

					@if ($sibling)
						@php
							$genders[] = $sibling->gender;
						@endphp

						<section
							id="gender"
							class="field relative"
						>

							<div class="flex items-center gap-2">
								<x-label class="field-label text-sm">Modello</x-label>
							</div>

							<div class="columns-10rem grid gap-2">

								@foreach ($genders as $key => $gender)
									@php
										$relatedProduct = null;

										if ($key && $product->design_id) {
										    $relatedProduct = App\Models\Ecommerce\Product::where('design_id', $product->design_id)
										        ->where('type_id', $sibling->id)
										        ->first();
										}
									@endphp

									@if (!$key || $relatedProduct)
										<a
											class="data-selector__label"
											@if ($relatedProduct) href="{{ $relatedProduct->url() }}" @endif
										>
											<input
												type="radio"
												class="cbx"
												name="gender_id"
												value="{{ $gender->id }}"
												{{ !$relatedProduct ? 'checked' : '' }}
											>

											{{ $gender->name }}
										</a>
									@endif
								@endforeach

							</div>

						</section>
					@endif
				@endif

				@if ($product->type->variants->count() || $product->type->main_type)
					<x-product.variants
						:product="$product"
						:productColors="$productColors"
					/>
				@endif

				{{-- Position --}}
				<livewire:product.options.position :$product />

				{{-- Logos --}}
				@if ($product->logoGroup())

					<div class="logo-field field">
						<x-label class="field-label text-sm">{{ $product->logoGroup()->name }}</x-label>

						<div
							class="grid-flow"
							data-spacing="400"
						>
							<div class="logo-field__list">

								{{-- Checkbox for no logo --}}
								@if (!$product->logoGroup()->forced)
									<label class="logo-field__item">
										<input
											type="radio"
											class="js-logo"
											name="logo_id"
											value="0"
											checked
										>
										{!! embed_icon('close') !!}
									</label>
								@endif

								{{-- checkboxes for extras --}}
								@foreach ($product->logos() as $logoKey => $logo)
									<label
										class="logo-field__item"
										title="{{ $logo->name }}"
									>
										<input
											type="radio"
											class="js-logo"
											name="logo_id"
											value="{{ $logo->id }}"
											{{ $product->logoGroup()->forced && !$logoKey ? 'checked' : null }}
										>
										<img
											class="img-fill"
											src="{{ $logo->image() }}"
											alt=""
										>
									</label>
								@endforeach

							</div>

							<div class="logo-field__preview js-logo-image-background {{ $product->logoGroup()->forced ? 'active' : null }}">

								@if ($product->logoGroup()->logos?->first()?->customPreview($product->type_id))
									<img
										class="logo-field__static-preview"
										src="{{ $product->logoGroup()->logos?->first()?->customPreview($product->type_id) }}"
										alt=""
									>
								@else
									<div class="logo-field__preview-wrapper grid-flow stack-content">

										<img
											class="logo-field__preview-image js-front-image"
											src="{{ $product->image(1024) }}"
										>

										<img
											class="logo-field__preview-logo js-logo-image"
											src=""
											alt=""
										>

									</div>
								@endif
							</div>
						</div>
					</div>

				@endif

				{{-- Quantity --}}
				<div
					id="qty_cont"
					class="field hidden"
				>
					<x-label class="field-label text-sm">Quantità</x-label>

					<input
						type="text"
						id="quantity"
						name="quantity"
						value="1"
					>
					<div id="qty_box">
						<h1
							id="qty_pls"
							class="qty_edit on"
						>+</h1>
						<h1
							id="qty_min"
							class="qty_edit off"
						>-</h1>
					</div>
				</div>

				{{-- Add to cart --}}
				<div class="mar-t-4 grid grid-cols-1 gap-4">

					@if ($warning)
						<x-alerts.warning :message="$warning->message" />
					@endif

					<x-button
						type="submit"
						id="submit"
						class="add-to-cart w-full"
					>
						{!! embed_icon('cart') !!}
						Aggiungi al carrello
					</x-button>

					@if (!$product->isCustom() && $product->blankProduct())
						<p class="">Vuoi acquistare il prodotto senza grafica? <a
								class="font-bold text-neutral-950"
								href="{{ $product->blankProduct()->url() }}"
							>Clicca qui</a>.</p>
					@endif

				</div>

				{{-- Info --}}
				<x-product.info :$product />

				{{-- @if ($product->design && in_array($product->type->tag, ['patch', 'hat']))
                    <x-product.accessories :$product />
                @endif --}}
			</div>
		</section>
	</form>
</div>
