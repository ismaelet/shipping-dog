@props(['title' => null, 'headerConfig' => []])

@push('styles')
    <style>
        .main-content {
            display: grid;
            grid-template-areas:
                'header'
                'main';
            grid-template-columns: minmax(0, 1fr);
            grid-template-rows: max-content 1fr;
            min-height: 100svh;
        }

        main {
            grid-area: main;
        }
    </style>
@endpush

<x-layouts.app :$title>
    <div class="main-content">
        <x-layouts.navigation.default-header :config="$headerConfig" />

        <main>
            <div class="content-grid min-h-full">
                {{ $slot }}
            </div>
        </main>
    </div>

    <x-layouts.navigation.default-footer />
</x-layouts.app>
