@props([
    'href' => null,
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'regular',
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
			--_padding-y: 0.9em;
			min-width: 12rem;
			font-size: 1rem;
			font-family: var(--font-heading);

			/* Primary button */
			&[data-variant="primary"] {
				position: relative;
				isolation: isolate;
				padding-block: var(--_padding-y);
				padding-inline: calc(var(--_padding-y) * 2);
				background-color: var(--color-primary-400);
				box-shadow: 0px 1rem 4rem -1rem color-mix(in hsl, var(--color-primary-400), transparent 60%);
				color: #fff;
				transition: opacity 0.2s, background-color 0.2s;

				&::before {
					content: "";
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
				&[data-color="secondary"] {
					background-color: var(--color-secondary-400);
					box-shadow: 0px 1rem 4rem -1rem color-mix(in hsl, var(--color-secondary-400), transparent 60%);

					&::before {
						background-image: linear-gradient(to right, var(--color-secondary-400), var(--color-secondary-500));
					}

				}

				/* Primary button: Neutral color */
				&[data-color="neutral"] {
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
				&[data-color="danger"] {
					background-color: var(--color-error-400);

					&::before {
						background-image: linear-gradient(to right, var(--color-error-400), var(--color-error-500));
					}

				}
			}

			/* Secondary button */
			&[data-variant="secondary"] {
				padding-block: calc(var(--_padding-y) - var(--spacing-border));
				padding-inline: calc((var(--_padding-y) - var(--spacing-border)) * 2);
				border: var(--spacing-border) solid var(--color-neutral-400);
				color: var(--color-neutral-heading);
				transition: border 0.2s, color 0.2s;

				/* Secondary button: Dark color scheme */
				.dark & {
					border-color: var(--color-neutral-400);
				}

				/* Secondary button: Primary color */
				&[data-color="primary"] {
					border-color: var(--color-primary-400);
					color: var(--color-primary-400);

					/* Secondary button: Danger color - hover state */
					&:hover {
						color: var(--color-primary-500);
						border-color: var(--color-primary-500);
					}
				}

				/* Secondary button: Danger color */
				&[data-color="danger"] {
					border-color: var(--color-error-400);
					color: var(--color-error-400);

					/* Secondary button: Danger color - hover state */
					&:hover {
						color: var(--color-error-500);
						border-color: var(--color-error-500);
					}
				}

				/* Secondary button: Hover state */
				&:hover {
					color: var(--color-neutral-heading);
					border-color: var(--color-neutral-heading);
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
			:where(&:has(svg)) {
				display: flex;
				align-items: center;
				justify-content: center;
				gap: 0.5em;

				svg {
					height: 1em;
				}
			}

			/*  Disabled state */
			&:disabled {
				filter: grayscale(1) opacity(0.5);
				cursor: default;

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
		data-size="{{ $size }}"
		data-color="{{ $color }}"
	>{{ $slot }}</a>
@else
	<button
		type="{{ $type }}"
		{{ $attributes->class($classes) }}
		data-variant="{{ $variant }}"
		data-size="{{ $size }}"
		data-color="{{ $color }}"
		@if ($confirmation) onclick="return confirm('{{ $confirmation }}')" @endif
	>{{ $slot }}</button>
@endif
