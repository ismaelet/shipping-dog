@props([
    'type' => 'text',
    'name' => null,
    'value' => null,
    'placeholder' => null,
    'required' => false,
])

@pushOnce('styles')
    <style>
        .open-input {
            border: none;
            border-bottom: var(--border-width) solid var(--color-neutral-300, #DDD);
            outline: none;
            color: var(--color-neutral-400);

            &:-webkit-autofill {
                color: var(--color-neutral-400);
                -webkit-box-shadow: 0 0 0 1000vw var(--color-background-400) inset;
            }
        }
    </style>
@endPushOnce

<input type="{{ $type }}" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}"
    {{ $required ? 'required' : '' }} class="open-input py-3 font-heading w-full" />
