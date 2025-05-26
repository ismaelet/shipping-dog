<form
	id="tags"
	action="{{ route('designs.update-tags', $design) }}"
	class="surface grid gap-4 p-6"
	method="POST"
	x-data
>
	@csrf

	<x-typography.component-heading>Tags</x-typography.component-heading>

	@if (session('saved-tags'))
		<x-alerts.success>Modifiche salvate</x-alerts.success>
	@endif

	<x-textarea
		name="tags"
		placeholder="(Opzionale) Puoi inserire fino a 10 tag separati da virgola. I tag rendono il tuo design più facile da trovare."
		@input="$el.nextElementSibling.classList.remove('hidden')"
	>{{ $design->tags?->tags }}</x-textarea>

	<div class="hidden">
		<x-button
			type="submit"
			size="small"
			class="w-full"
		>Salva</x-button>
	</div>

</form>
