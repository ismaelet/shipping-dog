@pushOnce('styles')
    <style>
        .success-message {
            padding-block: .5em;
            border: var(--border-width) solid var(--color-success-400);
            border-radius: calc(var(--border-radius-400) / 2);
            background-color: hsl(var(--color-success-400-hsl) / .3);
            box-shadow: 0px 2rem 4rem -1rem hsl(var(--color-success-400-hsl) / .5);
            text-align: center;
        }
    </style>
@endpushOnce

<p {{ $attributes->merge(['class' => 'success-message text-success']) }}">{{ $slot }}</p>
