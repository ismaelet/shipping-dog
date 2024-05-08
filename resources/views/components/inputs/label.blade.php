@pushOnce('styles')
	<style>

	</style>
@endPushOnce

<label
	class="font-semibold text-neutral-700"
	{{ $attributes }}"
>
	{{ $text ?? $slot }}
</label>
