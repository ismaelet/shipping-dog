@props(['entry'])

<article class="bg-background-400 flex flex-wrap items-center gap-3 rounded-2xl p-4 sm:flex-nowrap sm:gap-4">
	<img
		alt=""
		class="bg-surface-500 size-12 shrink-0 rounded-xl object-cover"
		src="{{ $entry->image() }}"
		loading="lazy"
	>

	<div class="min-w-0 flex-1">
		<p class="text-neutral-heading line-clamp-2 text-sm font-bold leading-5 sm:truncate">
			{!! $entry->title() !!}
		</p>

		@if ($subtitle = $entry->subtitle())
			<p class="text-neutral-text truncate pt-1 text-xs leading-4">
				{!! $subtitle !!}
			</p>
		@endif
	</div>

	<span
		class="text-neutral-text w-full text-right text-xs font-semibold leading-4 sm:w-auto sm:shrink-0 sm:whitespace-nowrap"
	>
		{{ $entry->date() }}
	</span>
</article>
