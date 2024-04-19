@props([
    'href' => null,
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'regular',
])

@php
    $classes = 'button px-16 rounded-full font-heading text-center text-sm sm:text-base';
@endphp

@pushOnce('styles')
    <style>
        .button {
            display: inline-block;
            min-width: 12rem;
            transition: color .2s, background-color .2s, border-color .2s;

            /* Icons handling */
            &:has(svg) {
                display: flex;
            }

            /* Primary button */
            &[data-variant="primary"] {
                --_color: var(--color-primary-400-hsl);
                --_shadow-intensity: 0.4;
                padding-block: 1em;
                background-color: hsl(var(--_color));
                box-shadow: 0 .25rem 3rem -1rem hsl(var(--_color) / var(--_shadow-intensity));
                color: #fff;

                /* Primary button: Dark color scheme */
                [data-color-scheme="dark"] &,
                [data-local-color-scheme="dark"] & {
                    --_shadow-intensity: 0.4;
                }

                /* Primary button: Hover */
                &:hover {
                    --_color: var(--color-primary-500-hsl);
                }
            }

            /* Secondary button */
            &[data-variant="secondary"] {
                --_border-width: 1px;
                --_border-color: currentColor;
                --_color: var(--color-neutral-700);
                padding-block: calc(1em - var(--border-width));
                border: var(--_border-width) solid var(--_border-color);
                color: var(--_color);

                /* Secondary button: Dark color scheme */
                [data-color-scheme="dark"] &,
                [data-local-color-scheme="dark"] & {
                    --_border-width: var(--border-width);
                    --_border-color: var(--color-neutral-300);
                }

                /* Secondary button: Hover */
                &:hover {
                    --_border-color: var(--color-primary-400);
                    --_color: var(--color-primary-400);
                }
            }

            /* Small version */
            &[data-size="small"] {
                min-width: 8rem;
                font-size: var(--font-size-300);
            }

            /* Auto width version */
            &[data-size="auto"] {
                width: max-content;
                min-width: unset;
                max-width: 100%;
            }

            /* Button with icons */
            &:has(svg) {
                gap: 0.5em;
            }
        }
    </style>
@endPushOnce

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}" data-variant="{{ $variant }}"
        data-size="{{ $size }}">{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->class($classes) }}" data-variant="{{ $variant }}"
        data-size="{{ $size }}">{{ $slot }}</button>
@endif
