@props([
    'title' => null,
    'description' => null,
    'thumbnail' => null,
])

@php
	$appName = config('app.name');
	$appDescription = config('custom.meta_data.description');
	$description = $description ?: $appDescription;
	$thumbnail = $thumbnail ?: Vite::image('thumbnail.jpg');

	$title = $title ? $title . ' | ' . $appName : $appName . ' | ' . config('custom.meta_data.short_description');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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

	<script>
		(() => {
			const theme = localStorage.getItem('theme') || 'light';

			const isDark = theme === 'dark' ||
				(theme === 'system' &&
					window.matchMedia('(prefers-color-scheme: dark)').matches);

			document.documentElement.classList.toggle('dark', isDark);
			document.documentElement.classList.toggle('light', !isDark);
			document.documentElement.style.colorScheme = isDark ? 'dark' : 'light';
		})();
	</script>

	<livewire:styles />

	@vite(['resources/css/app.css', 'resources/js/app.ts'])
	@stack('styles')
	@stack('scripts')

</head>

<body>
	{{ $slot }}

	<livewire:scripts />
</body>

</html>
