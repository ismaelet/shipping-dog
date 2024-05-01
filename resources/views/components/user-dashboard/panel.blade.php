@props(['title' => '', 'icon' => null])

@pushOnce('styles')
	<style>
		.user-panel {
			padding-inline: var(--content-padding);
			padding-block: var(--content-padding);
			border-radius: var(--border-radius-400);
			box-shadow: var(--shadow-big);
		}

		.user-panel__title {
			padding-bottom: var(--content-padding);
			margin-bottom: var(--content-padding);
			border-bottom: var(--border);
		}
	</style>
@endpushOnce

<section class="user-panel">

	{{-- Panel header --}}
	<div class="user-panel__title flex items-center gap-4">
		<span class="text-secondary-400 aspect-square w-6">
			{{ import_svg('icons/' . $icon) }}
		</span>

		<x-typography.component-heading>{{ $title }}</x-typography.component-heading>
	</div>

	{{-- Panel body --}}
	<div class="">
		{{ $slot }}
	</div>
</section>
