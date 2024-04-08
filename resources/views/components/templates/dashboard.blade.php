@props(['navigationGroups', 'colorScheme' => null])

@pushOnce('styles')
    <style>
        [data-color-scheme="dark"] {
            --theme-h: 220deg;
            --theme-dark-s: 25%;
            --border-color: var(--color-neutral-250);
            --border-width: 1px;
        }

        main {
            display: grid;
            grid-template-columns: max-content minmax(0, 1fr);
            background-color: var(--background-color);
        }

        .page-content {
            min-height: 100svh;
            padding: 2rem;
        }

        .page-wrapper {
            min-width: 250px;
        }
    </style>
@endpushOnce

<x-templates.empty :color-scheme="$colorScheme">
    <!-- Navigation bar -->
    <x-templates.dashboard.dashboard-navigation :navigation-groups="$navigationGroups" />
    <!-- Body -->
    <section class="page-content">
        <div class="page-wrapper">

            {{ $slot }}

        </div>
    </section>
</x-templates.empty>
