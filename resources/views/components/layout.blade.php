<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-color-scheme="{{ $colorScheme }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $description ?? $appDescription }}">
    <meta property="og:image" content="{{ Vite::image('thumbnail.jpg') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <title>
        {{ isset($title) ? $title . ' | ' . $appName : $appName . ' | ' . config('custom.meta_data.short_description') }}
    </title>

    <livewire:styles />
    @vite(['resources/css/main.css', 'resources/js/main.ts'])
    @stack('styles')
    @stack('scripts')

</head>

<body>
    {{ $slot }}

    <livewire:scripts />
</body>

</html>
