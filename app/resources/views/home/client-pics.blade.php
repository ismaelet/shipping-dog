{{-- Community --}}
<section id="community" aria-label="La community Shipping Dog" class="breakout mb-12 py-16 text-center">
    {{-- Heading --}}
    <p class="text-primary-500 text-xs font-bold tracking-[0.2em] uppercase">La community</p>

    <h2 class="text-neutral-heading t mx-auto mt-4 max-w-3xl text-4xl font-bold tracking-tight sm:text-6xl">
        Fatto per essere vissuto.
    </h2>

    <p class="mx-auto mt-5 max-w-120 leading-7">
        Tagga
        <a
            href="{{ config('custom.contacts.instagram.url') }}"
            target="_blank"
            rel="noopener noreferrer"
            class="text-primary-500 dark:text-neutral-heading font-semibold transition-colors hover:text-orange-500"
        >
            @shippingdog
        </a>
        o inviaci una foto del tuo look via
        <a
            href="{{ config('custom.contacts.email.url') }}"
            class="text-primary-500 dark:text-neutral-heading font-semibold transition-colors hover:text-orange-500"
        >
            e-mail
        </a>
        e ricevi un codice <span class="text-primary-400 font-semibold">sconto del 15%</span>.
    </p>

    {{-- Photo gallery --}}
    <div class="mt-10 grid grid-cols-2 gap-3 md:grid-cols-4 lg:grid-cols-5">
        @foreach ($customerPhotos as $customerPhoto)
            <x-community.photo-card :customerPhoto="$customerPhoto" />
        @endforeach
    </div>

    {{-- View all --}}
    <a
        href="{{ route('community') }}"
        class="hover:text-primary-500 mt-8 inline-flex items-center gap-2 text-sm font-bold transition-colors"
    >
        Guarda tutte le foto

        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            class="size-4"
            aria-hidden="true"
        >
            <path d="M5 12h14" />
            <path d="m13 6 6 6-6 6" />
        </svg>
    </a>
</section>
