@php
	$currentLocation = explode('user-dashboard.', request()->route()->getName())[1] ?? 'recap';
	$dashboardLocation = $link['dashboard_location'];
	$checked = $currentLocation == $dashboardLocation;
@endphp

<a href="{{ $link['url'] }}">
	<li @class([
		'px-4 transition-colors py-6',
		'bg-primary-400 text-neutral-900' => $checked,
		'transition-colors hover:text-primary' => !$checked,
	])>
		<p class="font-heading flex items-center gap-4 text-sm sm:text-base">
			<x-dynamic-component
				class="h-5 w-5"
				:component="'icon-' . $link['icon']"
			/>

			{{ $link['title'] }}
		</p>
	</li>
</a>
