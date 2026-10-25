<section id="design" aria-label="Apri il tuo shop" class="py-20 sm:px-8">
    <div class="dark mx-auto grid max-w-7xl overflow-hidden rounded-4xl bg-slate-950 text-white lg:grid-cols-2">
        {{-- Content --}}
        <div class="flex flex-col justify-center p-8 sm:p-14 lg:p-20">
            <p class="text-primary-300 text-xs font-bold tracking-[0.2em] uppercase">Open call</p>

            <h2 class="text-heading-400 mt-4 text-4xl leading-tight font-bold tracking-tight sm:text-6xl">
                Il tuo design.<br />
                La tua vetrina.
            </h2>

            <p class="mt-6 max-w-md leading-7">
                Trasforma le tue idee in prodotti e raggiungi una community che cerca qualcosa di autentico.
            </p>

            <x-button :href="route('open-shop')" class="mt-8 w-fit" color="neutral">
                Apri il tuo shop

                <x-icon-arrow />
            </x-button>
        </div>

        {{-- Image --}}
        <div class="h-120 lg:h-auto lg:min-h-112.5">
            <img
                src="{{ Vite::image('home/open-shop.webp') }}"
                alt="Apri il tuo negozio su Shipping Dog"
                loading="lazy"
                class="size-full object-cover"
            />
        </div>
    </div>
</section>
