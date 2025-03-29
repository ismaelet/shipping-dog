@push('styles')
    <style>
        .mobile-header {
            display: grid;
            grid-template-columns: max-content 0fr 1fr;
            align-items: center;
            padding-block: 1rem;
        }

        .mobile-header__column {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .mobile-header__column:nth-child(1) {
            justify-content: left;
        }

        .mobile-header__column:nth-child(2) {
            justify-content: center;
        }

        .mobile-header__column:nth-child(3) {
            justify-content: right;
        }
    </style>
@endpush

<div class="mobile-header border-bottom">
    {{-- Left bar --}}
    <div class="mobile-header__column">
        <x-navigation.header.logo />
    </div>

    {{-- Center bar --}}
    <div class="mobile-header__column">
    </div>

    {{-- Right bar --}}
    <div class="mobile-header__column">
        <x-navigation.header.hamburger-icon />
    </div>

    {{-- Mobile navigation --}}
    <x-navigation.header.mobile-navigation />
</div>
