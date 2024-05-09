<form
	class="flex justify-center"
	method="POST"
	x-data
>
	<x-inputs.button
		type="submit"
		variant="secondary"
		color="error"
		onclick="return confirm('Sei sicuro di voler eliminare definitivamente la grafica e tutti i prodotti associati?')"
	>
		{!! embed_icon('delete') !!}
		Elimina la grafica
	</x-inputs.button>
</form>
