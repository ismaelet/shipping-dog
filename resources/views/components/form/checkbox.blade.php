@props([
    'name' => null,
    'value' => null,
    'checked' => false,
    'text' => null,
    'required' => false,
])

@pushOnce('styles')
    <style>
        .checkbox {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
            width: fit-content;
        }

        .checkbox input {
            opacity: 0;
            position: absolute;
            pointer-events: none;
        }

        .checkbox>span {
            display: flex;
            width: calc(1.25rem + 2px);
            aspect-ratio: 1;
            margin-block: 2px;
            margin-left: 2px;
            border: var(--border-width) solid var(--border-clr-400);
            border-radius: 50%;
            cursor: pointer;
            transition: border-color 0.2s;
        }

        .checkbox input:checked+span {
            border-color: var(--fc-700);
        }

        .checkbox>span::after {
            content: '';
            width: 8px;
            aspect-ratio: 1;
            margin: auto;
            opacity: 0;
            background-color: var(--color-primary-400);
            border-radius: inherit;
            transition: opacity 0.2s;
        }

        .checkbox:hover span::after,
        .checkbox input:checked+span::after {
            opacity: 1;
        }

        @media (hover: none) {
            .checkbox input:not(:checked)+span {
                border-color: var(--border-clr-400);
            }

            .checkbox input:not(:checked)+span::after {
                opacity: 0;
            }
        }

        .checkbox p {
            line-height: 1;
            cursor: pointer;
        }
    </style>
@endPushOnce

<label {{ $attributes->class(['checkbox']) }}>

    <input type="checkbox" name="{{ $name }}" {{ $value ? 'value="' . $value : '"' }}
        {{ $checked ? 'checked' : '' }} {{ $required ? 'required' : '' }}>

    <span></span>

    <p>{!! $text !!}</p>

</label>
