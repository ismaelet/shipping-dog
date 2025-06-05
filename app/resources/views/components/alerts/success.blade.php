@pushOnce('styles')
	<style>
		.success-message {
			box-shadow: 0px 2rem 4rem -1rem hsl(var(--color-success-400-hsl) / .4);
		}
	</style>
@endpushOnce

<p
	{{ $attributes->merge(['class' => 'success-message text-success-400 px-4 py-2 border border-success-400 bg-success-400/10 text-center rounded']) }}
>{{ $slot }}</p>
