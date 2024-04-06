@push('styles')
    <style>
        main {
            display: grid;
            min-height: 100svh;
        }

        @supports not (min-height: 100svh) {
            main {
                min-height: 100vh;
            }
        }
    </style>
@endpush

<x-layout :color-scheme="$colorScheme ?? null">

    <main>{{ $slot }}</main>

</x-layout>
