@php
    $user = auth()->user();
@endphp

<section class="breakout">
    <div
        class="relative mx-auto mt-5 h-56 w-full overflow-hidden rounded-3xl bg-cover bg-center sm:h-80"
        style="background-image: url({{ $user->cover() }})"
    >
        <div class="dark:from-background-300/80 dark:via-background-300/45 from-background-300/50 via-background-300/30 absolute inset-0 bg-linear-to-r to-transparent"></div>
        <p class="text-heading-400 absolute bottom-8 left-8 hidden max-w-96 text-sm leading-6 text-shadow-lg/5 sm:bottom-12 sm:left-16 sm:block">
            Il tuo spazio personale per seguire ordini, collezionare design e far crescere le tue idee.
        </p>
    </div>

    <div class="relative grid grid-cols-1 justify-items-center">
        <div class="border-background-400 bg-primary-400 -mt-14 mb-4 h-28 w-28 rounded-full border-[5px]">
            <livewire:user-dashboard.icon />
        </div>

        <x-eyebrow>Account personale</x-eyebrow>
        <livewire:user-dashboard.name />
    </div>
</section>
