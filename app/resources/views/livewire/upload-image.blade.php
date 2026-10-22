<div>
	<div
		class="stack-content group relative grid aspect-square cursor-pointer place-items-center rounded bg-neutral-200"
		x-data
		x-on:click="$el.querySelector('input[type=file]').click()"
	>
		<input
			type="file"
			name="images[]"
			accept="image/*"
			class="absolute h-0 w-0 opacity-0"
			wire:model.live="image"
			wire:loading.attr="disabled"
		>

		@if ($imageExists)
			<img
				src="{{ FileHelper::storageAsset($imageUrl) }}"
				alt="Uploaded image"
				class="h-full w-full rounded-[inherit] object-cover"
			>
		@endif

		<div @class([
			'h-full w-full rounded-[inherit] bg-neutral-100/50    transition-opacity group-hover:opacity-100',
			'opacity-100' => !$imageExists,
			'opacity-0' => $imageExists,
		])>
		</div>

		<x-icon-upload @class([
			'relative h-8 w-8 transition-all group-hover:translate-y-0 group-hover:opacity-100',
			'opacity-100' => !$imageExists,
			'opacity-0 translate-y-4' => $imageExists,
		]) />
	</div>

	@error('image')
		<span class="error">{{ $message }}</span>
	@enderror

</div>
