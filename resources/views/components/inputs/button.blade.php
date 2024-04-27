@props([
    'href' => null,
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'regular',
    'color' => 'default',
])

@php
    $classes = 'button px-8 rounded-full font-heading text-center text-sm sm:text-base transition-colors';
@endphp

@pushOnce('styles')
    <style>
        .button {
            --icon-size: 1em;
            display: inline-block;
            min-width: 12rem;

            /* Primary button */
            &[data-variant="primary"] {
                padding-block: 1em;
                background-color: var(--color-primary-400);
                box-shadow: 0px 1rem 4rem -1rem hsl(var(--color-primary-400-hsl) / .6);
                color: #fff;

                /* Primary button: Hover state */
                &:hover {
                    background-color: var(--color-primary-500);
                }
            }

            /* Secondary button */
            &[data-variant="secondary"] {
                padding-block: calc(1em - var(--border-width));
                border: var(--border-width) solid var(--color-neutral-400);
                color: var(--color-neutral-700);

                /* Secondary button: Dark color scheme */
                [data-color-scheme="dark"] &,
                [data-local-color-scheme="dark"] & {
                    border-color: var(--color-neutral-250);
                }

                /* Secondary button: Error color */
                &[data-color="error"] {
                    border-color: var(--color-error-400);
                    color: var(--color-error-400);

                    /* Secondary button: Error color - hover state */
                    &:hover {
                        color: var(--color-error-500);
                        border-color: var(--color-error-500);
                    }
                }

                /* Secondary button: Hover state */
                &:hover {
                    color: var(--color-neutral-700);
                    border-color: var(--color-neutral-700);
                }
            }

            /* Sizes */
            &[data-size="small"] {
                min-width: 8rem;
                font-size: var(--font-size-300);
            }

            &[data-size="auto"] {
                min-width: unset;
                height: 100%;
                padding-block: unset;
            }

            /* If contains icons */
            &:has(svg) {
                display: flex;
                align-items: center;
                gap: 0.5em;
            }
        }
    </style>
@endPushOnce

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}" data-variant="{{ $variant }}"
        data-size="{{ $size }}" data-color="{{ $color }}">{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->class($classes) }}" data-variant="{{ $variant }}"
        data-size="{{ $size }}" data-color="{{ $color }}">{{ $slot }}</button>
@endif
