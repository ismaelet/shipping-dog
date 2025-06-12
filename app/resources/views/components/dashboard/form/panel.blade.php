@props(['title'])

<article class="grid grid-rows-[max-content]">
	@isset($title)
		<h3 class="pb-2 pl-8 text-xl font-semibold">{!! $title !!}</h3>
	@endisset

	<div
		{{ $attributes->class(['rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10 grid content-start gap-4 p-4']) }}
	>
		{{ $slot }}
	</div>
</article>
