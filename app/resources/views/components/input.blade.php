@props([
    'type' => 'text',
    'name' => null,
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'variant' => 'default',
])

@pushOnce('styles')
    <style>
        .input {
            padding-block: 0.75em;
        }
    </style>
@endPushOnce

<input
    type="{{ $type }}"
    name="{{ $name }}"
    value="{{ $value }}"
    placeholder="{{ $placeholder }}"
    {{ $required ? 'required' : '' }}
    {{ $attributes->class(['input input-style']) }}
    data-variant="{{ $variant }}"
/>
