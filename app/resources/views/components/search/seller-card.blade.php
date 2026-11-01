@props(['seller', 'featured' => false])

<a
    href="{{ $seller->url() }}"
    {{
        $attributes->class([
            'group bg-surface-500 focus-visible:outline-primary-400 block min-w-0 overflow-hidden rounded-xl transition-colors hover:bg-surface-500 focus-visible:outline-2 focus-visible:outline-offset-2 shadow-xl/5',
            'sm:grid sm:grid-cols-2' => $featured,
        ])
    }}
>
    <div @class(['aspect-3/2 overflow-hidden', 'sm:aspect-auto sm:h-full' => $featured])>
        <img
            src="{{ $seller->cover() }}"
            alt=""
            width="600"
            height="400"
            loading="lazy"
            class="size-full object-cover transition-transform duration-500 group-hover:scale-105 motion-reduce:transition-none"
        />
    </div>

    <div @class(['flex items-center gap-3 p-4', 'sm:flex-col sm:justify-center sm:text-center sm:p-8' => $featured])>
        <img
            src="{{ $seller->icon() }}"
            alt=""
            width="48"
            height="48"
            loading="lazy"
            @class(['size-12 shrink-0 rounded-full border-2 border-surface-200 object-cover', 'sm:size-20' => $featured])
        />
        <div class="min-w-0">
            <p class="text-heading-400 truncate font-semibold">{{ $seller->name }}</p>
            @if ($seller->products->count())
                <p class="text-sm">{{ $seller->products->count() }} prodotti</p>
            @endif
        </div>
    </div>
</a>
