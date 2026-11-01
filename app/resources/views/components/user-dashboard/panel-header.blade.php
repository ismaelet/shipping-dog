@props(['title', 'subtitle', 'text', 'buttonLabel' => null, 'buttonUrl' => null, 'metatext' => null])

<header class="border-border-400 flex flex-wrap items-end justify-between gap-4 border-b pb-6">
    <div>
        <x-eyebrow>{{ $subtitle }}</x-eyebrow>
        <x-heading variant="article">{{ $title }}</x-heading>
        <p class="mt-2 text-sm">{{ $text }}</p>
    </div>

    @if ($metatext)
        <p class="mt-2 text-sm">{{ $metatext }}</p>
    @endif

    @if ($buttonLabel && $buttonUrl)
        <x-button href="{{ $buttonUrl }}" variant="secondary" size="sm">
            {{ $buttonLabel }}
            <x-icon-download class="size-4" />
        </x-button>
    @endif
</header>
