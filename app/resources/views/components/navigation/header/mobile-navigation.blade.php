@push('styles')
    <style>
        .mobile-navigation {
            display: grid;
            align-items: center;
            position: fixed;
            inset: 0;
            z-index: 110;
            padding-inline: var(--content-padding);
            transition: opacity 200ms;

            &:not([data-status="open"]) {
                opacity: 0;
                pointer-events: none;

                & li {
                    opacity: 0;
                    translate: 0 2rem;
                }
            }
        }

        .mobile-navigation ul {
            display: flex;
            gap: 4rem;
            flex-direction: column;
            justify-content: space-between;
            min-height: 50vh;
            text-align: center;
        }

        .mobile-navigation li {
            --_delay-interval: 50ms;
            transition: opacity 200ms, translate 200ms;
            transition-delay: calc(var(--_delay-interval) * var(--_i));

            &:nth-child(1) {
                --_i: 1;
            }

            &:nth-child(2) {
                --_i: 2;
            }

            &:nth-child(3) {
                --_i: 3;
            }

            &:nth-child(4) {
                --_i: 4;
            }

            &:nth-child(5) {
                --_i: 5;
            }

            &:nth-child(6) {
                --_i: 6;
            }

            &:nth-child(7) {
                --_i: 7;
            }

            &:nth-child(8) {
                --_i: 8;
            }
        }
    </style>
@endpush

<nav class="mobile-navigation pt-15 bg-background" x-data="{ open: false }" @toggle_mobile_navigation.window="open = ! open"
    :data-status="open ? 'open' : 'closed'">
    <ul>
        @foreach (config('custom.header_navigation_links') as $link)
            <x-navigation.header.mobile-link :$link />
        @endforeach
    </ul>
</nav>
