@props([
    'product',
    'badge' => null,
    'displayBadge' => true,
    'light' => true,
    'categoryOnly' => false,
])

@php
    if ($displayBadge && ! $badge && $product->isPopular()) {
        $badge = 'Bestseller';
    } elseif ($displayBadge && ! $badge && $product->isNew()) {
        $badge = 'Novità';
    }
@endphp

@pushOnce('styles')
    <style>
        .product-card-img-wrapper {
            border-radius: clamp(var(--radius-xl), 8cqi, var(--radius-3xl));
        }
    </style>
@endpushOnce

<article class="product-card group min-w-0">
    {{-- Product image --}}
    <div class="@container">
        <div @class([
            'product-card-img-wrapper aspect-4/5 relative overflow-hidden rounded-xl',
            'light' => $light,
            'bg-surface-400' => $light,
            'bg-surface-500' => ! $light,
        ])>
            <a href="{{ $product->url() }}" class="block size-full">
                <img
                    src="{{ $product->image() }}"
                    alt="{{ $product->name }}"
                    loading="lazy"
                    class="size-full origin-center transform-gpu object-cover transition-transform duration-500 ease-out will-change-transform group-hover:scale-105"
                />
            </a>

            {{-- Badge --}}
            @if ($displayBadge && $badge)
                <span class="text-heading-400 pointer-events-none absolute top-4 left-4 rounded-full bg-white/90 px-3 py-1.5 text-[11px] font-bold tracking-wider uppercase">
                    {{ $badge }}
                </span>
            @endif

            {{-- Wishlist --}}
            <div class="absolute top-4 right-4 z-10">
                <livewire:wishlist-button
                    :product="$product"
                    :key="'wishlist-button-'.$product->id.'-'.$product->color_id.'-'.$product->position_id"
                />
            </div>
        </div>
    </div>

    {{-- Product details --}}
    <div class="pt-4">
        <div @class(['min-w-0', 'max-sm:w-full' => ! $light])>
            <div class="flex items-start justify-between gap-2">
                <h3 class="text-heading-400 min-w-0 text-sm font-semibold sm:text-base">
                    <a href="{{ $product->url() }}" class="hover:text-primary-300 wrap-break-word transition-colors">
                        {{ $categoryOnly ? $product->type->name : $product->name }}
                    </a>
                </h3>

                <p class="text-heading-400 shrink-0 text-sm font-semibold whitespace-nowrap sm:text-base">
                    {{ price($product->price()->price) }}
                </p>
            </div>

            @if (! $categoryOnly)
                <p class="mt-1 text-sm">{{ $product->type->name }}</p>
            @endif

            <p class="text-text-300 mt-1 text-xs">
                by
                <a href="{{ $product->seller->url() }}" class="hover:text-primary-300 transition-colors">
                    {{ $product->seller->name }}
                </a>
            </p>
        </div>
    </div>
</article>
