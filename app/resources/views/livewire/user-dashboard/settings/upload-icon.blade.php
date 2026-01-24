<form
	class="grid grid-cols-1 gap-8"
	method="POST"
	enctype="multipart/form-data"
>
	<x-heading.component-small class="dashboard-heading">Immagine
		profilo</x-heading.component-small>

	@session('success')
		<x-alerts.success>
			{{ session('success') }}
		</x-alerts.success>
	@endsession

	<div class="grid items-center justify-items-center gap-4">
		<x-label>Modifica l'immagine profilo:</x-label>

		<div class="relative aspect-square w-28 overflow-hidden rounded-full">
			<x-inputs.replace-image :image="$user->icon()" />
		</div>

		@error('image')
			<x-alerts.error :$message />
		@enderror
	</div>
</form>
