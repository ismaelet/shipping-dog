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
			padding-inline: .6em;
			background-color: var(--color-surface-400);
			border-radius: var(--input-border-radius);
			outline: var(--border);
			color: var(--color-neutral-heading);
			line-height: 2.8;
			transition: outline-color 0.2s ease-in-out;

			/* Focus state */
			&:focus {
				outline-color: var(--color-primary-400);
			}

			/* Has a warning */
			:has(> .input-warning) & {
				outline-color: var(--color-danger-400);
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
		}
	</style>
@endPushOnce

<input
	type="{{ $type }}"
	name="{{ $name }}"
	value="{{ $value }}"
	placeholder="{{ $placeholder }}"
	{{ $required ? 'required' : '' }}
	{{ $attributes->class(['input text-sm sm:text-base']) }}"
/>
