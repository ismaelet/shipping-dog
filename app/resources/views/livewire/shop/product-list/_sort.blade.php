<section class="flex items-center justify-end gap-3 md:col-span-2">
	<button
		type="button"
		wire:click="changeSort('new')"
		@class([
			'cursor-pointer border-b-2 border-transparent text-sm leading-8 transition-colors hover:text-primary-400',
			'!border-primary-400 text-primary-400' => $sort === 'new',
		])
	>
		Nuovi
	</button>

	<span class="text-neutral-text">·</span>

	<button
		type="button"
		wire:click="changeSort('popular')"
		@class([
			'cursor-pointer border-b-2 border-transparent text-sm leading-8 transition-colors hover:text-primary-400',
			'!border-primary-400 text-primary-400' => $sort === 'popular',
		])
	>
		Popolari
	</button>
</section>
