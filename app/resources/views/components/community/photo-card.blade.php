@props(['customerPhoto'])

<a
    href="{{ $customerPhoto->design?->url() ?? route('community') }}"
    class="group relative aspect-square overflow-hidden rounded-2xl bg-slate-100 last:hidden lg:last:block dark:bg-slate-800"
    aria-label="Foto di {{ $customerPhoto->full_name }}"
>
    <img
        src="{{ $customerPhoto->image() }}"
        alt="Look di {{ $customerPhoto->full_name }}"
        loading="lazy"
        class="size-full object-cover object-top transition-transform duration-700 group-hover:scale-105"
    />

    {{-- Hover overlay --}}
    <div class="absolute inset-0 flex items-end bg-linear-to-t from-slate-950/70 via-transparent to-transparent p-5 opacity-0 transition-opacity duration-300 group-hover:opacity-100 group-focus-visible:opacity-100">
        <div class="min-w-0 text-left text-white">
            @if ($customerPhoto->design)
                <p class="truncate font-semibold">{{ $customerPhoto->design->name }}</p>
            @endif

            <p class="mt-1 truncate text-sm text-white/75">{{ $customerPhoto->full_name }}</p>
        </div>
    </div>
</a>
