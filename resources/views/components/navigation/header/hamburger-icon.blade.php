@push('styles')
    <style>
        .hamburger-icon {
            --_height: 1.2rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: flex-end;
            position: relative;
            z-index: 120;
            height: var(--_height);
            aspect-ratio: 1.375;
            cursor: pointer;

            & span {
                height: 2px;
                background-color: currentColor;

                &:nth-child(1) {
                    width: 77%;
                    transition: translate 0.2s, rotate .2s;
                }

                &:nth-child(2) {
                    width: 59%;
                    transition: opacity 0.1s 0.1s, translate 0.2s;
                }

                &:nth-child(3) {
                    width: 100%;
                    transition: translate 0.2s, rotate .2s;
                }
            }

            &[data-status="open"] {
                color: var(--color-neutral-light-700);
                position: relative;
                z-index: 120;

                & span {
                    width: 100%;

                    &:nth-child(1) {
                        translate: 0 calc(var(--_height) / 2 - 1px);
                        rotate: 45deg;
                        transition: translate 0.2s, rotate .2s .2s;
                    }

                    &:nth-child(2) {
                        opacity: 0;
                        translate: -4rem 0;
                        transition: opacity 0.1s 0.1s, translate 0.2s;
                    }

                    &:nth-child(3) {
                        rotate: -45deg;
                        translate: 0 calc(var(--_height) / -2 + 1px);
                        transition: translate 0.2s, rotate .2s .2s;
                    }
                }
            }
        }
    </style>
@endpush

<div x-data="{ open: false }" @toggle_mobile_navigation.window="open = ! open">
    <div class="hamburger-icon" :data-status="open ? 'open' : 'closed'" @click="$dispatch('toggle_mobile_navigation')">
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>
