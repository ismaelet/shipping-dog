@props([
    'href' => null,
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'regular',
    'width' => 'default',
    'color' => 'default',
    'confirmation' => null,
])

@php
    $classes = 'button rounded-full font-heading text-center text-sm sm:text-base cursor-pointer';
@endphp

@pushOnce('styles')
    <style>
        .button {
            --icon-size: 1em;
            --_padding-y: 1.375em;
            --_padding-x: calc(var(--_padding-y) * 1.5);
            min-width: 12rem;
            font-size: 1rem;
            font-family: var(--font-heading);
            text-box: trim-both cap alphabetic;

            /* Primary button */
            &[data-variant='primary'] {
                position: relative;
                isolation: isolate;
                padding-block: var(--_padding-y);
                padding-inline: var(--_padding-x);
                background-color: var(--color-primary-400);
                box-shadow: 0px 1rem 4rem -1rem color-mix(in hsl, var(--color-primary-400), transparent 60%);
                color: #fff;
                transition:
                    opacity 0.2s,
                    background-color 0.2s;

                &::before {
                    content: '';
                    position: absolute;
                    inset: 0;
                    z-index: -1;
                    background-image: linear-gradient(to right, var(--color-primary-400), var(--color-primary-500));
                    border-radius: inherit;
                    transition: inherit;
                }

                &:hover {
                    background-color: var(--color-primary-300);
                }

                /* Primary button: Hover state */
                &:hover::before {
                    opacity: 0;
                }

                /* Primary button: Secondary color */
                &[data-color='secondary'] {
                    background-color: var(--color-secondary-400);
                    box-shadow: 0px 1rem 4rem -1rem color-mix(in hsl, var(--color-secondary-400), transparent 60%);

                    &::before {
                        background-image: linear-gradient(
                            to right,
                            var(--color-secondary-400),
                            var(--color-secondary-500)
                        );
                    }
                }

                /* Primary button: Neutral color */
                &[data-color='neutral'] {
                    color: var(--color-background-400);
                    background-color: var(--color-neutral-heading);
                    box-shadow: 0px 1rem 4rem -1rem color-mix(in hsl, var(--color-neutral-400), transparent 60%);

                    &:hover {
                        background-color: color-mix(in hsl, var(--color-neutral-heading), var(--color-primary-400) 20%);
                    }

                    &::before {
                        background: var(--color-neutral-heading);
                    }
                }

                /* Primary button: Danger color */
                &[data-color='danger'] {
                    background-color: var(--color-danger-400);

                    &::before {
                        background-image: linear-gradient(to right, var(--color-danger-400), var(--color-danger-500));
                    }
                }
            }

            /* Secondary button */
            &[data-variant='secondary'] {
                padding-block: calc(var(--_padding-y) - var(--spacing-border));
                padding-inline: calc(var(--_padding-x) - var(--spacing-border));
                background-color: var(--color-surface-400);
                border: var(--spacing-border) solid var(--color-neutral-border);
                color: var(--color-neutral-heading);
                transition:
                    border 0.2s,
                    color 0.2s;

                /* Secondary button: Dark color scheme */
                .dark & {
                    border-color: var(--color-neutral-border);
                }

                /* Secondary button: Primary color */
                &[data-color='primary'] {
                    border-color: var(--color-primary-400);
                    color: var(--color-primary-400);

                    /* Secondary button: Danger color - hover state */
                    &:hover {
                        color: var(--color-primary-500);
                        border-color: var(--color-primary-500);
                    }
                }

                /* Secondary button: Danger color */
                &[data-color='danger'] {
                    background-color: transparent;
                    border-color: var(--color-danger-400);
                    color: var(--color-danger-300);

                    svg {
                        color: var(--color-danger-400);
                    }

                    /* Secondary button: Danger color - hover state */
                    &:hover {
                        border-color: var(--color-danger-500);
                        color: var(--color-danger-400);
                    }
                }

                /* Secondary button: Hover state */
                &:hover {
                    color: var(--color-neutral-heading);
                    border-color: var(--color-neutral-heading);
                }
            }

            /* Sizes */
            &[data-size='small'] {
                min-width: 8rem;
                font-size: var(--text-sm);
            }

            &[data-size='auto'] {
                min-width: unset;
                height: 100%;
                padding-block: unset;
            }

            &[data-width='auto'] {
                min-width: unset;
                width: auto;
            }

            /* If contains icons */
            :where(&:has(svg)) {
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 0.5em;
                line-height: 0.7;

                svg {
                    height: 1em;
                    margin-block: -0.5em;
                }
            }

            /*  Disabled state */
            &:disabled {
                filter: grayscale(1) opacity(0.5);
                cursor: default;
                pointer-events: none;

                &::before {
                    opacity: 0;
                }
            }
        }
    </style>
@endPushOnce

@if ($href)
    <a
        href="{{ $href }}"
        {{ $attributes->class($classes) }}
        data-variant="{{ $variant }}"
        data-color="{{ $color }}"
        data-size="{{ $size }}"
        data-width="{{ $width }}"
    >{{ $slot }}</a>
@else
    <button
        type="{{ $type }}"
        {{ $attributes->class($classes) }}
        data-variant="{{ $variant }}"
        data-color="{{ $color }}"
        data-size="{{ $size }}"
        data-width="{{ $width }}"
        @if ($confirmation) onclick="return confirm('{{ $confirmation }}')" @endif
    >
        {{ $slot }}
    </button>
@endif
