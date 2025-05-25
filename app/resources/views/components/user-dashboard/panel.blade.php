@props(['title' => null, 'icon' => null])

@pushOnce('styles')
	<style>
		.dashboard-panel-title {
			padding-bottom: var(--content-spacing);
			margin-bottom: var(--content-spacing);
			border-bottom: var(--border);
		}
	</style>
@endpushOnce

<section class="dashboard-panel surface p-6">

	{{-- Panel header --}}
	<div class="dashboard-panel-title flex items-center gap-4">
		@if ($icon)
			<span class="text-primary aspect-square w-6">
				{!! embed_icon($icon) !!}
			</span>
		@endif

		<x-typography.component-heading>{{ $title }}</x-typography.component-heading>
	</div>

	{{-- Panel body --}}
	<div class="">
		{{ $slot }}
	</div>
</section>
