@push('styles')
    <style>
        .desktop-header {
            display: grid;
            grid-template-columns: max-content 0fr 1fr;
            align-items: center;
            padding-block: 1rem;
        }

        .desktop-header__column {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .desktop-header__column:nth-child(1) {
            justify-content: left;
        }

        .desktop-header__column:nth-child(2) {
            justify-content: center;
        }

        .desktop-header__column:nth-child(3) {
            justify-content: right;
        }
    </style>
@endpush

<div class="desktop-header border-bottom">
    <!-- Left bar -->
    <div class="desktop-header__column">
        <x-navigation.header.logo />
    </div>

    <!-- Center bar -->
    <div class="desktop-header__column">
    </div>

    <!-- Right bar -->
    <div class="desktop-header__column">
        <x-navigation.header.desktop-navigation />
    </div>
</div>
