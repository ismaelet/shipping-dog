@props(['title', 'subtitle', 'icon', 'variant' => 'default'])

<article {{ $attributes->class(['p-6 border-radius rounded-lg grid content-start gap-3 surface']) }}>
    {{-- Heading --}}
    <div class="grid gap-1">
        <div class="flex justify-between items-center ">
            {{-- Title --}}
            @switch($variant)
                @case('big')
                    <h3 class="font-semibold">{{ $title }}</h3>
                @break

                @default
                    <h3 class="text-sm font-medium">{{ $title }}</h3>
            @endswitch

            {{-- Icon --}}
            @isset($icon)
                <span class="w-4 h-4">
                    {!! embed_icon($icon) !!}
                </span>
            @endisset
        </div>

        {{-- Subtitle --}}
        @isset($subtitle)
            <p class="text-sm text-neutral-350">{{ $subtitle }}</p>
        @endisset
    </div>

    <div class="grid gap-1">
        {{-- Value --}}
        @isset($value)
            <div class="text-3xl font-bold text-neutral-700">{{ $value }}</div>
        @endisset

        {{-- Content --}}
        @isset($content)
            {{ $content }}
        @endisset

        {{-- Action --}}
        @isset($action)
            <div class="text-sm text-neutral-350">
                {{ $action }}
            </div>
        @endisset

        {{-- Footer --}}
        @isset($footerNotes)
            <p class="text-sm text-neutral-350">{{ $footerNotes }}</p>
        @endisset
    </div>
</article>
