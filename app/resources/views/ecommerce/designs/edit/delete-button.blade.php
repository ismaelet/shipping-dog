<form
	class="flex justify-center"
	method="POST"
	action="{{ route('designs.destroy', $design) }}"
	x-data
>
	@csrf
	@method('DELETE')

	<x-button
		type="submit"
		variant="secondary"
		color="danger"
		onclick="return confirm('Sei sicuro di voler eliminare definitivamente la grafica e tutti i prodotti associati?')"
	>
		{!! embed_icon('delete') !!}
		Elimina la grafica
	</x-button>
</form>
