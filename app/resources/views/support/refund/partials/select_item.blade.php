@php
    $sizes = $orderItem->product_with_trashed->type->sizes->where('id', '!=', $orderItem->size_id);
@endphp

<div class="card col-span-full grid grid-cols-subgrid items-center">
    <div class="h-full">
        <img src="{{ $orderItem->image() }}" class="h-full rounded object-cover" />
    </div>

    <div class="justify-self-center p-4 pb-0 sm:p-0">
        <x-heading variant="article-small">{{ $orderItem->product_with_trashed->name }}</x-heading>

        <p class="mt-2">{{ $orderItem->description() }}</p>
    </div>

    <div class="p-4 sm:py-4 sm:pr-4" x-data="{ showSizeSelector: false }">
        <x-label>Azione richiesta:</x-label>
        <x-select
            name="action_{{ $orderItem->id }}"
            class="w-full"
            @change="showSizeSelector = $event.target.value === 'change'"
        >
            <option value>Nessuna</option>

            @if ($orderItem->product_with_trashed->type->sizes->count() > 1)
                <option value="change">Cambio</option>
            @endif

            <option value="refund">Rimborso</option>
        </x-select>

        <div class="js-size-selector mt-4" x-show="showSizeSelector" x-cloak>
            <x-label class="">Taglia desiderata:</x-label>
            <x-select name="size_{{ $orderItem->id }}" class="w-full">
                @foreach ($sizes as $size)
                    <option value="{{ $size->id }}">{{ $size->name }}</option>
                @endforeach
            </x-select>
        </div>
    </div>
</div>
