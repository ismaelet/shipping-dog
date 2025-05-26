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
				box-shadow: 0px 1rem 4rem -1rem hsl(var(--color-primary-400-hsl) / .4);
				color: #fff;
				transition: opacity 0.2s;


				&::before {
					content: "";
					position: absolute;
					z-index: -1;
					border-radius: inherit;
					inset: 0;
					background-image: linear-gradient(to right, var(--color-primary-400), var(--color-primary-500));
					transition: inherit;
				}

				/* Primary button: Hover state */
				&:hover::before {
					opacity: 0;
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
				padding-block: calc(var(--_padding-y) - var(--border-width));
				padding-inline: calc((var(--_padding-y) - var(--border-width)) * 2);
				border: var(--border-width) solid var(--color-neutral-400);
				color: var(--color-neutral-700);
				transition: border 0.2s;

				/* Secondary button: Dark color scheme */
				.dark & {
					border-color: var(--color-neutral-400);
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
			:where(&:has(svg)) {
				display: flex;
				align-items: center;
				justify-content: center;
				gap: 0.5em;
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
		{{ $attributes->class($classes) }}"
		data-variant="{{ $variant }}"
		data-size="{{ $size }}"
		data-color="{{ $color }}"
	>{{ $slot }}</a>
@else
	<button
		type="{{ $type }}"
		{{ $attributes->class($classes) }}"
		data-variant="{{ $variant }}"
		data-size="{{ $size }}"
		data-color="{{ $color }}"
		@if ($confirmation) onclick="return confirm('{{ $confirmation }}')" @endif
	>{{ $slot }}</button>
@endif
