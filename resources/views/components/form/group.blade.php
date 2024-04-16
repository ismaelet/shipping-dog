@pushOnce('styles')
    <style>
        .form-group {
            grid-template-columns: minmax(0, 1fr);

            @media (min-width: 768px) {
                &:has([data-size]) {
                    grid-template-columns: repeat(4, minmax(0, 1fr));
                }

                & [data-size="1"] {
                    grid-column: span 1;
                }

                & [data-size="2"] {
                    grid-column: span 2;
                }

                & [data-size="3"] {
                    grid-column: span 3;
                }

                & [data-size="4"],
                & :not([data-size]) {
                    grid-column: span 4;
                }
            }
        }
    </style>
@endpushOnce

<div class="form-group grid gap-x-2 gap-y-1 grid-cols-1">
    {{ $slot }}
</div>
