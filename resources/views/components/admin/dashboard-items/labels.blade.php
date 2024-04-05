@php
    $icon = $icon ?? null;
@endphp

<article class="p-6 border-radius rounded-lg grid gap-2 surface">
    {{-- Heading --}}
    <div class="flex justify-between items-center">
        <h3 class="text-sm font-medium">{{ $title }}</h3>

        @if ($icon)
            <span class="h-4">
                {!! embed_icon($icon) !!}
            </span>
        @endif
    </div>

    <div>
        {{-- Value --}}
        <div class="text-3xl font-bold text-neutral-700">{{ $value }}</div>

        {{-- Action --}}

        {{-- Footer notes --}}
        @if ($footerNotes)
            <p class="text-sm text-neutral-300">{{ $footerNotes }}</p>
        @endif
    </div>
</article>
