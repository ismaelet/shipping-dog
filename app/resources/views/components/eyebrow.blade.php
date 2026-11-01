@props(['variant' => 'default'])

@pushOnce('styles')
    <style>
        .eyebrow {
            margin-bottom: 0.5rem;
            color: var(--color-primary-400);
            font-size: var(--text-xs);
            font-weight: var(--font-weight-bold);
            letter-spacing: 0.18em;
            text-transform: uppercase;
            text-box: trim-both cap alphabetic;

            .dark & {
                color: var(--color-primary-300);

                &[data-variant='alternative'] {
                    color: var(--color-primary-200);
                }
            }
        }
    </style>
@endpushOnce

<p {{ $attributes->merge(['class' => 'eyebrow']) }} data-variant="{{ $variant }}">{{ $slot }}</p>
