@props(['image', 'name', 'autoSubmit' => false])

<div
	class="stack-content grid grid-rows-1"
	x-data
	@click="$el.querySelector('input').click()"
>
	<input
		type="file"
		name="{{ $name ?? 'file' }}"
		class="hidden"
		accept=".jpg,.jpeg,.png,.webp"
		@if ($autoSubmit) @input="if ($el.files.length) $el.closest('form').submit()" @endif
	>

	{{-- Image --}}
	<img
		class="h-full w-full object-contain"
		src="{{ $image }}"
	>

	{{-- Overlay --}}
	<div
		class="stack-content group grid h-full w-full cursor-pointer justify-items-center opacity-0 transition-opacity hover:opacity-100"
	>
		<div class="bg-background h-full w-full opacity-70"></div>

		<div class="relative w-12 translate-y-4 text-neutral-700 transition-transform group-hover:translate-y-0">
			<?= embed_icon('upload') ?></div>
	</div>
</div>
