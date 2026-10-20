@props([
    'name' => null,
    'value' => null,
    'placeholder' => null,
    'required' => false,
])

@pushOnce('styles')
	<style>
		.textarea {
			min-height: 6em;
			padding: .25em .5em;
			background-color: var(--color-surface-400);
			border-radius: var(--input-border-radius);
			outline: var(--border);
			color: var(--color-neutral-heading);
			transition: outline-color 0.2s ease-in-out;

			/* Focus state */
			&:focus {
				outline-color: var(--color-primary-400);
			}

			/* Autofill resets */
			&:-webkit-autofill {
				outline: var(--spacing-border) solid var(--color-neutral-border) !important;
				-webkit-box-shadow: 0 0 0 100vw var(--color-surface-400) inset !important;
				box-shadow: 0 0 0 100vw var(--color-surface-400) inset !important;
				-webkit-text-fill-color: var(--color-neutral-heading) !important;
				transition: none !important;
			}

			&:focus:-webkit-autofill {
				outline: var(--spacing-border) solid var(--color-primary-400) !important;
			}

			/* Has a warning */
			:has(> .error-warning) & {
				outline-color: var(--color-error-400);

				&:-webkit-autofill,
				&:focus:-webkit-autofill {
					outline-color: var(--color-error-400) !important;
				}
			}
		}
	</style>
@endPushOnce

<textarea
 name="{{ $name }}"
 value="{{ $value }}"
 placeholder="{{ $placeholder }}"
 {{ $required ? 'required' : '' }}
 {{ $attributes->class(['textarea text-sm sm:text-base']) }}"
>{{ $slot }}</textarea>
