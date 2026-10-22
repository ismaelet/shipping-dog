@props([
    'options' => null,
])

@pushOnce('styles')
	<style>
		@layer components {
			.select {
				padding-block: .75em;
				cursor: pointer;
			}

			.select:hover {
				background: var(--color-surface-500);
			}

			.select-arrow {
				display: none;
			}

			/* Customizable select API */
			@supports (appearance: base-select) {

				.select,
				.select::picker(select) {
					appearance: base-select;
				}

				.select::picker-icon {
					display: none;
				}

				.select-arrow {
					display: block;
				}

				.select::picker(select) {
					margin-top: 0.5rem;
					padding: 0.375rem;
					border: 1px solid var(--color-neutral-border);
					border-radius: 1rem;
					background: var(--color-surface-400);
					color: var(--color-neutral-heading);
					box-shadow: 0 12px 32px rgb(0 0 0 / 30%);
				}

				.select option {
					padding: 0.75rem 0.875rem;
					border-radius: 0.625rem;
					background: transparent;
					color: var(--color-neutral-text);
					font-size: 0.875rem;
					font-weight: 500;
					cursor: pointer;
					transition: background-color 150ms, color 150ms;
				}

				.select option:hover,
				.select option:focus-visible {
					background: alpha(from rgb(255 255 255) / 8%);
					color: var(--color-neutral-heading);
				}

				.select option:checked {
					background: alpha(from var(--color-primary-300) / 12%);
					color: var(--color-primary-300);
					font-weight: 600;
				}

				.select option::checkmark {
					color: var(--color-primary-300);
				}
			}
		}
	</style>
@endPushOnce

<div class="relative">
	<select {{ $attributes->merge(['class' => 'select input-style relative']) }}>
		@if ($options)
			@foreach ($options as $optionValue => $label)
				<option
					value="{{ $optionValue }}"
					@if ($optionValue == $value) selected @endif
				>
					{{ $label }}
				</option>
			@endforeach
		@else
			{{ $slot }}
		@endif



	</select>
	<x-icon-arrow-simple-down class="select-arrow pointer-events-none absolute right-4 top-1/2 size-3 -translate-y-1/2" />
</div>
