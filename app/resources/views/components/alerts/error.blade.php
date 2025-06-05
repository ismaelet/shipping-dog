@props(['message' => null])

@pushOnce('styles')
	<style>
		.error-message {
			box-shadow: 0px 2rem 4rem -1rem hsl(var(--color-error-400-hsl) / .4);
		}
	</style>
@endpushOnce

<p
	{{ $attributes->merge(['class' => 'error-message text-error-400 px-4 py-2 border border-error-400 bg-error-400/10 text-center rounded']) }}
>{{ $slot }}{{ $message }}</p>
