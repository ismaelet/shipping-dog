@props(['product'])

@php
    $sizes = $product->type->sizes;
    $showTable = $product->type->measures?->count() && $product->type->category == 'c';

    $label = $product->type->category == 'c' ? 'Taglia' : 'Dimensione';
@endphp

@pushOnce('styles')
    <style>
        .product-options--size {
            grid-template-columns: repeat(auto-fit, minmax(5rem, 1fr));
        }
    </style>
@endpushOnce

<div x-data>
    <x-product.options.label>
        <span>{{ $label }}</span>

        @if ($showTable)
            <button
                type="button"
                class="text-primary-200 hover:text-primary-400 cursor-pointer text-sm transition-colors"
                @click="$dispatch('open-modal', { name: 'product-size-guide' })"
            >
                info taglie
            </button>
        @endif
    </x-product.options.label>

    <div class="product-options columns-[5rem]">
        @foreach ($sizes as $size)
            <label class="product-option">
                <input
                    type="radio"
                    name="size_id"
                    value="{{ $size->id }}"
                    @checked($loop->first)
                    @click="$dispatch('change-size', { id: {{ $size->id }} })"
                />
                {{ $size->name }}
            </label>
        @endforeach
    </div>

    {{-- Size table --}}
    @if ($showTable)
        <x-product.measure-table :$product :$showTable />
    @endif
</div>
