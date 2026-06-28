@use('App\Models\Ecommerce\Product\ProductSize')

@props(['product', 'productColors', 'defaultColor', 'defaultSize', 'defaultPosition', 'warning'])

@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/product-data.css') }}"
	>
@endpushOnce

<div class="item-data breakout pad-t-3 item-type--{{ $product->type->tag }} mb-8">

	<div class="content gap3 al-top grid">
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
				<x-product.price :$product />

				{{-- Colors --}}
				@if ($product->colors->count())
					<livewire:product.options.color
						:$product
						:defaultColorId="$defaultColor->id"
					/>
				@endif

				{{-- Size --}}
				@if ($product->type->sizes->count())
					<x-product.options.size
						:$product
						:defaultSizeId="$defaultSize->id"
					/>
				@endif

				{{-- Gender --}}
				@if ($product->type->gender)
					<x-product.options.gender :$product />
				@endif

				@if ($product->type->variants->count() || $product->type->main_type)
					<x-product.variants
						:product="$product"
						:productColors="$productColors"
					/>
				@endif

				{{-- Position --}}
				<livewire:product.options.position
					:product="$product"
					:defaultPositionId="$defaultPosition->id"
				/>

				{{-- Logos --}}
				@if ($product->logoGroup())
					<x-product.options.logo :$product />
				@endif

				<div class="mar-t-4 grid grid-cols-1 gap-4">

					{{-- Warning --}}
					@if ($warning)
						<x-alerts.warning :message="$warning->message" />
					@endif

					{{-- Add to cart --}}
					<livewire:product.add-to-cart
						:product="$product"
						:colorId="$defaultColor?->id"
						:sizeId="$defaultSize?->id"
						:positionId="$defaultPosition?->id"
					/>

					{{-- Blank product --}}
					@if (!$product->isCustom() && $product->blankProduct())
						<p class="">Vuoi acquistare il prodotto senza grafica? <a
								class="font-bold text-neutral-950"
								href="{{ $product->blankProduct()->url() }}"
							>Clicca qui</a>.</p>
					@endif

				</div>

				{{-- Info --}}
				<x-product.info :$product />
			</div>
		</section>
	</div>
</div>
