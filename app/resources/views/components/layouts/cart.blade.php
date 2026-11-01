@pushOnce('styles')
    <style>
        .card {
            display: grid;
            gap: 1.5rem;
            padding: 1.5rem;

            > .heading {
                padding-bottom: 0.5rem;
                margin-bottom: 0.5rem;
                border-bottom: 1px solid var(--color-border-400);
            }
        }
    </style>
@endpushOnce

<x-layouts.main>
    <div class="full-width bg-surface-400 relative py-20 text-center sm:py-24">
        <img
            class="fill-width absolute h-full w-full object-cover opacity-80"
            src="{{ Vite::image('auth/login-background.webp') }}"
        />

        <x-heading variant="section" level="1" class="relative"
            >Ca<span class="text-primary-400">rr</span>ello</x-heading>
    </div>

    <x-cart.flow-bar />

    <div class="content mb-20 grid grid-cols-1">{{ $slot }}</div>
</x-layouts.main>
