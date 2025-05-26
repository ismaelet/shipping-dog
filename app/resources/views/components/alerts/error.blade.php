@pushOnce('styles')
	<style>
		@layer utilities {
			.error-message {
				padding-block: .5em;
				border: var(--border-width) solid var(--color-error-400);
				border-radius: calc(var(--border-radius-400) / 2);
				background-color: hsl(var(--color-error-400-hsl) / .1);
				box-shadow: 0px 2rem 4rem -1rem hsl(var(--color-error-400-hsl) / .4);
				text-align: center;

				:where(&:has(svg)) {
					--icon-size: 1em;
					display: flex;
					justify-content: center;
					align-items: center;
					gap: .5em;
				}
			}
		}
	</style>
@endpushOnce

<p {{ $attributes->merge(['class' => 'error-message text-error-400']) }}>{{ $slot }}</p>
