@props(['title', 'subtitle', 'text', 'buttonLabel' => null, 'buttonUrl' => null, 'metatext' => null])


<header class="border-neutral-border flex flex-wrap items-end justify-between gap-4 border-b pb-6">
	<div>
		<p class="text-primary-400 text-sm font-bold uppercase tracking-[0.2em]">{{ $subtitle }}</p>
		<h2 class="font-heading text-neutral-heading mt-1 text-2xl font-semibold">{{ $title }}</h2>
		<p class="text-neutral-text mt-2 text-sm">
			{{ $text }}
		</p>
	</div>

	@if ($metatext)
		<p class="text-neutral-text mt-2 text-sm">
			{{ $metatext }}
		</p>
	@endif

	@if ($buttonLabel && $buttonUrl)
		<x-button
			href="{{ $buttonUrl }}"
			variant="secondary"
			size="small"
		>
			{{ $buttonLabel }}
			<x-icon-download class="size-4" />
		</x-button>
	@endif
</header>
