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
            border-radius: var(--border-radius-100);
            outline: var(--border);
            line-height: 2.8;
            background-color: var(--color-surface-400);
            transition: outline-color 0.2s ease-in-out;

            &:focus {
                outline-color: var(--color-primary-400);
            }

            :has(> .error-warning) & {
                outline-color: var(--color-error-400);
            }
        }
    </style>
@endPushOnce

<input type="{{ $type }}" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}"
    {{ $required ? 'required' : '' }} {{ $attributes->class(['input text-sm sm:text-base']) }}" />
