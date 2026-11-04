@use('App\Models\Ecommerce\Product\ProductSize')

@props(['product', 'productColors', 'defaultColor', 'defaultSize', 'defaultPosition', 'warning'])

@pushOnce('styles')
    <style>
        .product-main-content {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            align-items: flex-start;

            @media (width >= 48rem) {
                grid-template-columns: 55fr 45fr;
            }
        }

        .product-options {
            display: grid;
            gap: 0.5rem;
        }

        .product-option {
            padding: 0.875rem 1rem;
            background-color: var(--color-surface-400);
            border: 1px solid var(--color-border-400);
            border-radius: 100vw;
            color: var(--color-heading-400);
            font-weight: var(--font-weight-bold);
            text-align: center;
            text-box: trim-both cap alphabetic;
            cursor: pointer;
            transition:
                background-color 0.2s,
                border-color 0.2s,
                color 0.2s;

            &:hover {
                background-color: var(--color-surface-500);
            }

            &:has(input:checked) {
                background-color: alpha(from var(--color-primary-400) / 15%);
                color: var(--color-primary-400);
                border-color: var(--color-primary-400);
            }

            input {
                display: none;
            }
        }
    </style>
@endpushOnce

<div class="mt-8">
    <div class="product-main-content gap-12">
        <livewire:product.images :$product />

        <section class="">
            <div class="grid grid-cols-1 gap-8">
                {{-- Title --}}
                <div class="">
                    <x-eyebrow variant="alternative">{{ $product->type->name }}</x-eyebrow>
                    <x-heading variant="section-small">{{ $product->name }}</x-heading>

                    <p class="mt-2 text-sm">
                        by
                        <a class="hover:text-primary-200 transition-colors" href="{{ $product->seller->url() }}">
                            {{ $product->seller->name }}
                        </a>
                    </p>
                </div>

                {{-- Price --}}
                <x-product.price :$product />

                {{-- Options --}}
                <div class="grid grid-cols-1 gap-4">
                    {{-- Colors --}}
                    @if ($product->colors->count())
                        <livewire:product.options.color :$product :defaultColorId="$defaultColor->id" />
                    @endif

                    {{-- Size --}}
                    @if ($product->type->sizes->count())
                        <x-product.options.size :$product :defaultSizeId="$defaultSize->id" />
                    @endif

                    {{-- Gender --}}
                    @if ($product->type->gender)
                        <x-product.options.gender :$product />
                    @endif

                    @if ($product->type->variants->count() || $product->type->main_type)
                        <x-product.variants :product="$product" :productColors="$productColors" />
                    @endif

                    {{-- Position --}}
                    <livewire:product.options.position :product="$product" :defaultPositionId="$defaultPosition->id" />

                    {{-- Logos --}}
                    @if ($product->logoGroup())
                        {{-- <x-product.options.logo :$product /> --}}
                    @endif
                </div>

                <div class="mar-t-4 grid grid-cols-1 gap-4">
                    {{-- Warning --}}
                    @if ($warning)
                        <x-alert type="error" :message="$warning->message" />
                    @endif

                    {{-- Add to cart --}}
                    <livewire:product.add-to-cart
                        :product="$product"
                        :colorId="$defaultColor?->id"
                        :sizeId="$defaultSize?->id"
                        :positionId="$defaultPosition?->id"
                    />

                    {{-- Blank product --}}
                    @if (! $product->isCustom() && $product->blankProduct())
                        <p class="">
                            Vuoi acquistare il prodotto senza grafica?
                            <a class="text-link" href="{{ $product->blankProduct()->url() }}">Clicca qui</a>.
                        </p>
                    @endif
                </div>
            </div>
        </section>
    </div>

    {{-- Additional information --}}
    <div class="border-border-400 mt-12 grid grid-cols-[minmax(0,1.55fr)_minmax(0,1fr)] items-start gap-6 border-b pb-12 max-sm:grid-cols-1">
        {{-- Product information --}}
        <x-product.info :$product />

        {{-- Seller information --}}
        <aside @class([
            'flex flex-col justify-between gap-5 rounded-3xl border border-border-400 bg-background-300 p-7 max-sm:p-5',
        ])>
            <p class="text-primary-300 text-xs font-bold tracking-wide uppercase">Il creator</p>

            <div class="flex flex-wrap items-start justify-between gap-4 sm:flex-nowrap">
                <a href="{{ $product->seller->url() }}" class="flex items-center gap-4">
                    <img
                        class="size-14 rounded-full object-cover"
                        src="{{ $product->seller->icon() }}"
                        alt=""
                        loading="lazy"
                    />
                    <div>
                        <h2 class="font-heading text-heading-400 text-lg font-semibold">
                            {{ $product->seller->name }}
                        </h2>
                        <p class="text-text-400 text-xs">Membro dal {{ $product->seller->createdAtString() }}</p>
                    </div>
                </a>

                <livewire:follow-button :seller="$product->seller" />
            </div>

            <x-button :href="$product->seller->url()" variant="secondary" size="sm">
                Visita il negozio
                <x-icon-shop />
            </x-button>
        </aside>
    </div>
</div>
