@pushOnce('styles')
    <style>
        .color-swatch-secondary {
            clip-path: polygon(0 0, 100% 0, 100% 50%, 0 50%);
            rotate: 135deg;
        }
    </style>
@endpushOnce

@props(['color'])
<label
    {{ $attributes->class('has-checked:border-primary-400 border-border-400 aspect-square w-8 cursor-pointer overflow-hidden rounded-full border-3 transition-colors relative') }}
    style="background-color: {{ $color->primary_color_hex }}"
    title="{{ $color->name }}"
>
    @if ($color->secondary_color_hex)
        <span
            class="color-swatch-secondary absolute inset-0"
            style="background-color: #{{ $color->secondary_color_hex }}"
        ></span>
    @endif

    {{ $slot }}
</label>
