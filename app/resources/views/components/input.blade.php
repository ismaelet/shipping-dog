@props([
    'type' => 'text',
    'name' => null,
    'value' => null,
    'placeholder' => null,
    'required' => false,
])

@pushOnce('styles')
	<style>
		.input {
			padding-inline: 1em;
			background-color: alpha(from var(--color-background-400) / 70%);
			border-radius: var(--radius-lg);
			outline: var(--spacing-border) solid var(--color-neutral-border);
			color: var(--color-neutral-heading);
			padding-block: .75em;
			transition: outline-color 0.2s ease-in-out;

			/* Focus state */
			&:focus {
				outline-color: var(--color-primary-400);
			}

			&::placeholder {
				color: var(--color-neutral-text);
			}

			/* Autofill resets */
			&:-webkit-autofill {
				outline: var(--spacing-border) solid alpha(from var(--color-neutral-border) / 20%) !important;
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
				outline-color: var(--color-danger-400);

				&:-webkit-autofill,
				&:focus:-webkit-autofill {
					outline-color: var(--color-danger-400) !important;
				}
			}
		}
	</style>
@endPushOnce

<input
	type="{{ $type }}"
	name="{{ $name }}"
	value="{{ $value }}"
	placeholder="{{ $placeholder }}"
	{{ $required ? 'required' : '' }}
	{{ $attributes->class(['input text-sm sm:text-base']) }}
/>
