@props([
    'type' => 'text',
    'name' => null,
    'value' => null,
    'placeholder' => null,
    'required' => false,
])

@pushOnce('styles')
    <style>
        .input {
            padding-inline: .5em;
            background-color: var(--color-surface-400);
            border-radius: var(--input-border-radius);
            outline: var(--border);
            color: var(--color-neutral-700);
            line-height: 2.8;
            transition: outline-color 0.2s ease-in-out;

            &:focus {
                outline-color: var(--color-primary-400);
            }

            :has(> .input-warning) & {
                outline-color: var(--color-error-400);
            }

            &:-webkit-autofill {
                outline: var(--border-width) solid var(--border-color) !important;
                -webkit-box-shadow: 0 0 0 100vw var(--color-surface-400) inset !important;
                box-shadow: 0 0 0 100vw var(--color-surface-400) inset !important;
                -webkit-text-fill-color: var(--color-neutral-700) !important;
                transition: none !important;
            }

            &:focus:-webkit-autofill {
                outline: var(--border-width) solid var(--color-primary-400) !important;
            }
        }
    </style>
@endPushOnce

<input type="{{ $type }}" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}"
    {{ $required ? 'required' : '' }} {{ $attributes->class(['input text-sm sm:text-base']) }}" />
