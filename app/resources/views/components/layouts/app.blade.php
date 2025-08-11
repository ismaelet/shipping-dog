@props([
    'title' => null,
    'colorScheme' => null,
    'description' => null,
    'thumbnail' => null,
])

@php
	$appName = config('app.name');
	$appDescription = config('custom.meta_data.description');
	$description = $description ?: $appDescription;
	$thumbnail = $thumbnail ?: Vite::image('thumbnail.jpg');

	$title = $title ? $title . ' | ' . $appName : $appName . ' | ' . config('custom.meta_data.short_description');

	$colorScheme =
	    $colorScheme ?:
	    (isset($_COOKIE['color-scheme']) && in_array($_COOKIE['color-scheme'], ['light', 'dark'])
	        ? $_COOKIE['color-scheme']
	        : config('custom.meta_data.default_color_scheme'));
@endphp

<!DOCTYPE html>
<html
	class="{{ $colorScheme }}"
	lang="{{ str_replace('_', '-', app()->getLocale()) }}"
>

<head>
	<meta charset="utf-8">
	<meta
		name="viewport"
		content="width=device-width, initial-scale=1"
	>
	<meta
		name="description"
		content="{{ $description }}"
	>
	<meta
		property="og:image"
		content="{{ $thumbnail }}"
	>
	<link
		rel="icon"
		href="{{ asset('favicon.ico') }}"
	>
	<title>
		{{ $title }}
	</title>

	<livewire:styles />

	@vite(['resources/css/app.css', 'resources/js/app.js'])
	@stack('styles')
	@stack('scripts')

</head>

<body>
	{{ $slot }}

	{{-- <x-warnings.wip /> --}}
	<livewire:scripts />
</body>

</html>
