@props(['message' => null])

@pushOnce('styles')
	<style>
		.error-message {
			box-shadow: 0px 2rem 4rem -1rem hsl(var(--color-danger-400-hsl) / .4);
		}
	</style>
@endpushOnce

<p
	{{ $attributes->merge(['class' => 'error-message text-danger-400 px-4 py-2 border border-danger-400 bg-danger-400/10 text-center rounded']) }}
>{{ $slot }}{{ $message }}</p>
