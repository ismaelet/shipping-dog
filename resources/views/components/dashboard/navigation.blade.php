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

            .navigation-header {
                padding-top: 2.5rem;

                svg {
                    width: 100%;
                    max-height: 3rem;
                }
            }

            .button-shutdown {
                border-radius: var(--border-radius-400);
            }
        }
    </style>
@endpushOnce

<aside class="navigation js-navigation">
    <div class="navigation-content">

        <div class="">
            <!-- Dashboard title -->
            <div class="navigation-header text-neutral-700 grid gap-2 mb-12">
                {!! embed_svg('logo/logo') !!}
                <p class="font-heading font-semibold text-2xl tracking-wider ">{{ config('app.name') }}</p>
            </div>

            <!-- Dashboard groups -->
            <div class="grid gap-4 js-open-navigation">

                @foreach ($navigationGroups as $navigationGroup)
                    <x-dashboard.navigation-group :navigation-group="$navigationGroup" />
                @endforeach

                <form class="mt-2 grid" method="POST">
                    <x-form.button variant="secondary" size="small" class="button-shutdown" name="logout">
                        <?= embed_icon('exit') ?>
                        Esci
                    </x-form.button>
                </form>
            </div>

        </div>
</aside>
