@props(['title' => null, 'icon' => null, 'meta' => null])

@pushOnce('styles')
	<style>
		.dashboard-panel-title {
			margin-bottom: var(--spacing-content-padding);
			border-bottom: var(--border);
		}
	</style>
@endpushOnce

<section class="dashboard-panel border-neutral-border bg-surface-400 rounded-xl border p-6">

	{{-- Panel header --}}
	<div class="dashboard-panel-title flex items-center gap-4">
		@if ($icon)
			<span class="text-primary-400 aspect-square w-6">
				<x-dynamic-component :component="'icon-' . $icon" />
			</span>
		@endif

		<x-heading.component>{{ $title }}</x-heading.component>

		@if ($meta)
			<span class="text-neutral-text ml-auto text-sm font-semibold">{{ $meta }}</span>
		@endif
	</div>

	{{-- Panel body --}}
	<div class="">
		{{ $slot }}
	</div>
</section>
