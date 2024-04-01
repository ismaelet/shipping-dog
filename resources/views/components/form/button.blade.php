@props([
    'href' => null,
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'regular',
])

@php
    $classes = 'button px-16 rounded-full font-heading text-center text-sm sm:text-base transition-colors';
@endphp

@pushOnce('styles')
    <style>
        .button {
            display: inline-block;
            min-width: 12rem;

            &:has(svg) {
                display: flex;
            }

            &[data-variant="primary"] {
                padding-block: 1em;
                background-color: var(--color-primary-400);
                box-shadow: 0px 2rem 4rem -1rem var(--color-primary-400);
                color: #fff;

                &:hover {
                    background-color: var(--color-primary-500);
                }
            }

            &[data-variant="secondary"] {
                padding-block: calc(1em - var(--border-width));
                border: var(--border-width) solid currentColor;
                color: var(--color-primary-400);
            }

            &[data-size="small"] {
                min-width: 8rem;
                font-size: var(--font-size-300);
            }

            [data-local-theme="dark"] & {
                background-color: #fff;
                color: var(--color-primary-400);

                &:hover {
                    background-color: var(--color-primary-400);
                    color: #fff;
                }
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
