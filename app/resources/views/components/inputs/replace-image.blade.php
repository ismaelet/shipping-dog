@props(['image'])

@pushOnce('styles')
	<style>
		.replace-image-overlay {
			background-image: linear-gradient(135deg,
					rgba(0, 0, 0, 0.3),
					var(--color-background));
		}
	</style>
@endpushOnce

<div class="stack-content group relative grid h-full w-full place-items-center">
	<img
		src="{{ $image }}"
		class="h-full w-full object-cover"
		alt=""
	>

	<div
		class="replace-image-overlay h-full w-full opacity-0 transition-opacity group-hover:opacity-100"
		wire:loading.class="opacity-100"
		wire:loading.class.remove="opacity-0 group-hover:opacity-100"
	></div>

	<x-icon-upload
		class="group-hover:translate-0 w-1/2 max-w-12 translate-y-4 text-white opacity-0 transition-all group-hover:opacity-100"
		wire:loading.remove
	/>

	<x-loading-spinner wire:loading.flex />

	<input
		type="file"
		accept="image/*"
		@class(['absolute inset-0 h-full w-full cursor-pointer opacity-0'])
		wire:model="image"
		wire:loading.remove
	/>
</div>
