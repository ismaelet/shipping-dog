@use('App\Helpers\ArrayHelper')

@props(['title'])

@php
	$backRouteName = ArrayHelper::explodeLast('.', request()->route()->getName())[0] . '.index';
	$backRouteParameters = request()->index_parameters ? json_decode(request()->index_parameters, true) : null;
	$backRoute = Route::has($backRouteName)
	    ? ($backRouteParameters
	        ? route($backRouteName, $backRouteParameters)
	        : route($backRouteName))
	    : null;
	$title = str_replace('Modifica:', '<span class=\'font-light\'>Modifica:</span> ', $title);
@endphp

<div class="flex items-center gap-4">

	@if (Route::has($backRouteName))
		<a
			class="aspect-square w-10 rounded border transition-colors hover:text-neutral-700"
			href="{{ $backRoute }}"
		>
			{!! embed_icon('previous', ['fill-none', 'h-1/3']) !!}
		</a>
	@endif

	<x-typography.section-heading>{!! $title !!}</x-typography.section-heading>

</div>
