@pushOnce('styles')
	<style>
		:where(.eyebrow) {
			color: var(--color-primary-300);
		}

		.eyebrow {
			margin-bottom: 0.75rem;
			font-size: var(--text-xs);
			font-weight: var(--font-weight-bold);
			letter-spacing: 0.18em;
			text-transform: uppercase;
			text-box: trim-both cap alphabetic;
		}
	</style>
@endpushOnce

<p {{ $attributes->merge(['class' => 'eyebrow']) }}>
	{{ $slot }}
</p>
