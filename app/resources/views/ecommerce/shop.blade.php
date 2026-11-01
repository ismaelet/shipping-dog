@pushOnce('styles')
    <style>
        :root {
            --spacing-breakout: 1600px;
            --spacing-content: 1440px;
        }

        .hero-image {
            object-position: 70% 50%;
        }

        .hero-gradient {
            background: linear-gradient(
                to right,
                alpha(from var(--color-slate-950) / 90%),
                alpha(from var(--color-slate-950) / 55%) 30%,
                transparent 50%
            );
        }
    </style>
@endpushOnce

<x-layouts.main :$location :$title>
    <section class="full-width">
        <div class="breakout py-5">
            <div class="relative isolate flex items-center overflow-hidden rounded-4xl bg-slate-950 px-6 py-12 sm:px-10 lg:h-96 lg:px-16 lg:py-0">
                <img
                    src="{{ Vite::image('shop/hero-background.webp') }}"
                    alt=""
                    class="hero-image absolute inset-0 size-full object-cover opacity-90"
                />

                <div class="hero-gradient absolute inset-0"></div>

                <div class="relative max-w-2xl">
                    <p class="text-primary-300 text-xs font-bold tracking-[0.2em] uppercase">Shop</p>

                    <h1 class="font-heading mt-6 max-w-2xl text-4xl font-semibold tracking-[-0.04em] text-white sm:text-6xl lg:text-7xl">
                        Trova il tuo prossimo preferito.
                    </h1>

                    <p class="mt-5 max-w-lg text-sm leading-7 text-white/70 sm:text-base">
                        T-shirt e felpe originali, create da artisti indipendenti per chi non vuole vestirsi come tutti
                        gli altri.
                    </p>
                </div>
            </div>
        </div>

        <livewire:shop.product-list />
    </section>
</x-layouts.main>
