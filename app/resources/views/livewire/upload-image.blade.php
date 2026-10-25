@php
    $aspectClass = match ($aspect) {
        'cover' => 'aspect-[2.5/1]',
        'portrait' => 'aspect-[3/4]',
        default => 'aspect-square',
    };
@endphp

<div
    x-data
    class="bg-background-400 group relative cursor-pointer"
    @class([$aspectClass])
    x-on:click="$refs.file.click()"
>
    <input
        x-ref="file"
        type="file"
        accept="image/*"
        aria-label="Carica immagine"
        class="sr-only"
        wire:model="image"
        wire:loading.attr="disabled"
        wire:target="image"
        tabindex="-1"
    />

    {{-- Existing image --}}
    @if ($this->imageSrc)
        <img src="{{ $this->imageSrc }}" alt="Uploaded image" class="{{ $aspectClass }} w-full object-cover" />
    @else
        <div class="text-text-400 flex h-full w-full items-center justify-center"></div>
    @endif

    {{-- Hover overlay --}}
    <div
        @class([
            'absolute size-full inset-0 bg-black/40 transition-opacity',
            'opacity-0 group-hover:opacity-100' => $this->imageExists,
            'opacity-100' => ! $this->imageExists,
        ])
    ></div>

    {{-- Upload icon --}}
    <button
        wire:loading.remove
        wire:target="image"
        @class([
            'absolute cursor-pointer inset-0 z-10 text-heading-400 transition-opacity grid place-items-center group-hover:opacity-100',
            'opacity-0' => $this->imageExists,
            'opacity-50' => ! $this->imageExists,
        ])
    >
        <x-icon-upload @class([
            'size-1/3 max-w-10 max-h-10',
            'translate-y-2 transition-transform group-hover:translate-y-0' => $this->imageExists,
        ]) />
    </button>

    {{-- Loading state --}}
    <div
        wire:loading.flex
        wire:target="image"
        class="absolute inset-0 z-20 items-center justify-center bg-black/50 text-white"
    >
        <x-icon-loading class="size-8" />
    </div>
</div>
