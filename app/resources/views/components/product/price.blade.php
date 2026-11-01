@pushOnce('styles')
    <style>
        .product-price {
            color: var(--color-heading-400);
            font-size: var(--text-3xl);
            font-weight: var(--font-weight-bold);
        }
    </style>
@endpushOnce

<div class="">
    @if ($product->userIsOwner())
        {{-- Price when there's a discount --}}
        <div class="flex flex-wrap items-center gap-4">
            <p class="product-price">{{ $product->fullPriceString() }}</p>
            <p class="text-danger-400 text-lg font-semibold line-through">{{ $product->sellerPriceString() }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <p class="dark:text-primary-200 text-primary-300 text-sm">
                Prezzo scontato{{ $product->userIsOwner() ? ' solo per te' : '' }}
            </p>

            @if ($product->userIsOwner())
                <x-help.info>
                    Questo prezzo speciale è visibile a te, perché sei il proprietario di questo articolo. I clienti
                    vedranno solo il prezzo normale di
                    <span class="text-heading-400 font-bold">{{ $product->fullPriceString() }}</span>
                </x-help.info>
            @endif
        </div>
    @else
        {{-- Normal price --}}
        <p class="product-price">{{ $product->customerPriceString() }}</p>
    @endif
</div>
