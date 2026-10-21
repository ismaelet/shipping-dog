@props(['title', 'subtitle', 'text', 'buttonLabel' => null, 'buttonUrl' => null])


<header class="border-neutral-border flex flex-wrap items-start justify-between gap-4 border-b pb-6">
	<div>
		<p class="text-primary-400 text-sm font-bold uppercase tracking-[0.2em]">{{ $subtitle }}</p>
		<h2 class="font-heading text-neutral-heading mt-1 text-2xl font-semibold">{{ $title }}</h2>
		<p class="text-neutral-text mt-2 text-sm">
			{{ $text }}
		</p>
	</div>

	@if ($buttonLabel && $buttonUrl)
		<a
			href="{{ $buttonUrl }}"
			class="border-neutral-border text-neutral-heading hover:bg-surface-500 flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-semibold transition-colors"
		>
			{{ $buttonLabel }}
			<x-icon-download class="size-4" />
		</a>
	@endif
</header>
