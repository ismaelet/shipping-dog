@props(['entry'])

<article class="bg-background-400 flex flex-wrap items-center gap-3 rounded-2xl p-4 sm:flex-nowrap sm:gap-4">
    <img
        alt=""
        class="bg-surface-500 size-12 shrink-0 rounded-xl object-cover"
        src="{{ $entry->image() }}"
        loading="lazy"
    />

    <div class="min-w-0 flex-1">
        <p class="text-heading-400 line-clamp-2 text-sm leading-5 font-bold sm:truncate">{!! $entry->title() !!}</p>

        @if ($subtitle = $entry->subtitle())
            <p class="text-text-400 truncate pt-1 text-xs leading-4">{!! $subtitle !!}</p>
        @endif
    </div>

    <span class="text-text-400 w-full text-right text-xs leading-4 font-semibold sm:w-auto sm:shrink-0 sm:whitespace-nowrap">
        {{ $entry->date() }}
    </span>
</article>
