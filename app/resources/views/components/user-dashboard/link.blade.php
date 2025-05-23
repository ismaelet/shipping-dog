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
			<span class="aspect-square w-5">
				{!! embed_icon($link['icon']) !!}
			</span>

			{{ $link['title'] }}
		</p>
	</li>
</a>
