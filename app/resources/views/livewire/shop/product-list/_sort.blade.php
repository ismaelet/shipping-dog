@pushOnce('styles')
	<style>
		.sort-select {
			appearance: none;
			min-width: 10rem;
			height: 2.5rem;
			padding: 0.5rem 2.5rem 0.5rem 1.25rem;
			border: 1px solid var(--color-neutral-border);
			border-radius: 9999px;
			background: var(--color-surface-400);
			color: var(--color-neutral-heading);
			font: inherit;
			font-size: 0.875rem;
			font-weight: 600;
			cursor: pointer;
			transition: border-color 150ms, background-color 150ms;
		}

		.sort-select:hover {
			border-color: var(--color-neutral-border);
			background: var(--color-surface-500);
		}

		.sort-select:focus-visible {
			outline: 2px solid var(--color-primary-500);
			outline-offset: 2px;
		}

		/* Customizable select API */
		@supports (appearance: base-select) {

			.sort-select,
			.sort-select::picker(select) {
				appearance: base-select;
			}

			.sort-select::picker-icon {
				display: none;
			}

			.sort-select::picker(select) {
				margin-top: 0.5rem;
				padding: 0.375rem;
				border: 1px solid var(--color-neutral-border);
				border-radius: 1rem;
				background: var(--color-surface-400);
				color: var(--color-neutral-heading);
				box-shadow: 0 12px 32px rgb(0 0 0 / 30%);
			}

			.sort-select option {
				padding: 0.75rem 0.875rem;
				border-radius: 0.625rem;
				background: transparent;
				color: var(--color-neutral-text);
				font-size: 0.875rem;
				font-weight: 500;
				cursor: pointer;
				transition: background-color 150ms, color 150ms;
			}

			.sort-select option:hover,
			.sort-select option:focus-visible {
				background: alpha(from rgb(255 255 255) / 8%);
				color: var(--color-neutral-heading);
			}

			.sort-select option:checked {
				background: alpha(from var(--color-primary-300) / 12%);
				color: var(--color-primary-300);
				font-weight: 600;
			}

			.sort-select option::checkmark {
				color: var(--color-primary-300);
			}
		}
	</style>
@endPushOnce

<label class="text-neutral-text flex items-center gap-2 text-sm">
	<span>Ordina:</span>

	<span class="relative">
		<select
			class="sort-select"
			wire:change="changeSort($event.target.value)"
		>
			<option
				value="popular"
				@selected($sort === 'popular')
			>
				Popolari
			</option>
			<option
				value="new"
				@selected($sort === 'new')
			>
				Nuovi
			</option>

			@if ($seller)
				<option
					value="custom"
					@selected($sort === 'custom')
				>
					Personalizzati
				</option>
			@endif
		</select>

		<x-icon-arrow-simple-down class="pointer-events-none absolute right-4 top-1/2 size-3 -translate-y-1/2" />
	</span>
</label>
