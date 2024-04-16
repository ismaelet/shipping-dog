@props(['navigationGroups'])

@pushOnce('styles')
    <style>
        .navigation {
            position: relative;
            width: 18rem;
            transition: width 0.4s;
        }

        @scope (.navigation)

            {
            .navigation-content {
                position: sticky;
                top: 0;
                height: 100svh;
                padding-inline: 2.5rem;
                overflow: auto;
                scrollbar-width: thin;
                scrollbar-color: var(--color-surface-400) var(--color-background);
            }
        }
    </style>
@endpushOnce

<aside class="navigation js-navigation">
    <div class="navigation-content">

        <div class="">
            <!-- Dashboard title -->
            <div class="navigation-header text-center text-neutral-700 grid gap-2 mb-12 mt-10">
                {!! embed_svg('logo/logo', ['w-full', 'h-10']) !!}
                <p class="font-heading font-semibold text-2xl tracking-wider ">{{ config('app.name') }}</p>
            </div>

            <!-- Dashboard groups -->
            <div class="grid gap-4 js-open-navigation">

                @foreach ($navigationGroups as $navigationGroup)
                    <x-dashboard.navigation.group :navigation-group="$navigationGroup" />
                @endforeach

                <a class="button-shutdown surface rounded gap-2 leading-10 mt-3 font-heading transition-colors hover:text-neutral-700"
                    href="{{ route('logout') }}">
                    {!! embed_icon('exit', ['w-1em']) !!}
                    Esci
                </a>
            </div>

        </div>
</aside>
