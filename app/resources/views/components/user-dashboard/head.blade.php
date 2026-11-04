@php
    $user = auth()->user();
@endphp

<section class="breakout">
    <livewire:user-dashboard.cover />

    <div class="relative grid grid-cols-1 justify-items-center">
        <div class="border-background-400 bg-surface-400 -mt-14 mb-4 h-28 w-28 rounded-full border-[5px]">
            <livewire:user-dashboard.icon />
        </div>

        <x-eyebrow>Account personale</x-eyebrow>
        <livewire:user-dashboard.name />
    </div>
</section>
