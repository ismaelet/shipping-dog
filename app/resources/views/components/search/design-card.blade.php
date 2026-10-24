@props(['design'])

@php($product = $design->main_product)

<article {{ $attributes->class('group relative min-w-0') }}>
    <div class="bg-surface-400 aspect-square overflow-hidden rounded-xl">
        <img
            src="{{ $product->image() }}"
            alt="{{ $product->name }}"
            width="400"
            height="400"
            loading="lazy"
            class="size-full object-cover transition-transform duration-500 ease-out group-hover:scale-105 motion-reduce:transition-none"
        />
    </div>

    <h3 class="text-neutral-heading mt-3 truncate font-semibold">
        <a
            href="{{ $product->url() }}"
            class="focus-visible:outline-primary-400 after:absolute after:inset-0 after:rounded-xl focus-visible:outline-2 focus-visible:outline-offset-2"
        >{{ $product->name }}</a>
    </h3>

    <p class="mt-1 truncate text-sm">
        by
        <a
            href="{{ $design->seller->url() }}"
            class="hover:text-primary-400 focus-visible:outline-primary-400 relative z-10 underline-offset-2 transition-colors hover:underline focus-visible:outline-2"
        >{{ $design->seller->name }}</a>
    </p>

    <p class="mt-1 text-xs">
        Disponibile su
        <span class="text-neutral-heading font-medium">{{ $design->products_count }} {{ $design->products_count === 1 ? 'articolo' : 'articoli' }}</span>
    </p>
</article>
