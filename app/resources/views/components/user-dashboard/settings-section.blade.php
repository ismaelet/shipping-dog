@props(['title', 'text', 'icon', 'tone' => 'primary'])

@php
	$toneClasses = match ($tone) {
	    'detail-1' => 'bg-detail-1-400/15 text-detail-1-300',
	    'detail-2' => 'bg-detail-2-400/15 text-detail-2-300',
	    default => 'bg-primary-400/15 text-primary-300',
	};
@endphp

<section {{ $attributes->class(['border-neutral-border border-t pt-8 first:border-t-0 first:pt-0']) }}>
	<header class="flex items-center gap-3">
		<span class="{{ $toneClasses }} grid size-10 shrink-0 place-items-center rounded-full">
			<x-dynamic-component
				:component="'icon-' . $icon"
				class="size-5"
			/>
		</span>

		<div>
			<h3 class="font-heading text-neutral-heading text-xl font-semibold">{{ $title }}</h3>
			<p class="text-neutral-text text-sm">{{ $text }}</p>
		</div>
	</header>

	<div class="mt-6">
		{{ $slot }}
	</div>
</section>
