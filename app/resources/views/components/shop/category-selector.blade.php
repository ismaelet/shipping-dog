@props([
    'title',
    'types',
    'genderId' => null,
    'selectedTypeId' => null,
    'selectedGenderId' => null,
    'defaultOpen' => false,
])

@php
    $genderId = $genderId !== null ? (int) $genderId : null;

    $isSelected = $types->contains(function ($type) use ($selectedTypeId, $selectedGenderId, $genderId) {
        return (int) $selectedTypeId === (int) $type->id &&
            ($selectedGenderId !== null ? (int) $selectedGenderId : null) === $genderId;
    });
@endphp

@pushOnce('styles')
    <style>
        .shop-category-selector {
            grid-template-rows: auto 0fr;
            align-content: flex-start;
            transition: grid-template-rows 0.2s ease-in-out;

            &:has(button[aria-expanded='true']) {
                grid-template-rows: auto 1fr;
            }
        }
    </style>
@endpushOnce

<div
    x-data=" { open: @js($isSelected || $defaultOpen) }"
    class="shop-category-selector border-border-400 grid grid-cols-1 border-b last:border-0"
>
    {{-- Category heading --}}
    <button
        type="button"
        @click="open = ! open"
        :aria-expanded="open.toString()"
        @class([
            'flex w-full cursor-pointer items-center justify-between px-4 py-3.5 text-left text-sm font-bold transition-colors',
            'text-primary-300' => $isSelected,
            'text-heading-400 hover:bg-surface-500' => ! $isSelected,
        ])
    >
        <span>{{ $title }}</span>

        <x-icon-arrow-simple-down
            class="size-3 shrink-0 -rotate-90 transition-transform duration-300"
            x-bind:class="{ 'rotate-0!': open }"
        />
    </button>

    {{-- Product types --}}
    <div class="overflow-hidden">
        @foreach ($types as $type)
            @php
                $checked =
                    (int) $selectedTypeId === (int) $type->id &&
                    ($selectedGenderId !== null ? (int) $selectedGenderId : null) === $genderId;
            @endphp

            <button
                type="button"
                wire:key="category-{{ $genderId ?? 'accessories' }}-{{ $type->id }}"
                wire:click="selectType({{ $type->id }}, {{ $genderId === null ? 'null' : $genderId }})"
                @class([
                    'flex min-h-11 w-full cursor-pointer items-center px-8 py-2 text-left text-sm font-medium transition-colors',
                    'bg-background-400 text-heading-400' => $checked,
                    'bg-surface text-text-400 hover:bg-background-400 hover:text-heading-400' => ! $checked,
                ])
                aria-pressed="{{ $checked ? 'true' : 'false' }}"
            >
                {{ $type->names }}
            </button>
        @endforeach
    </div>
</div>
