<section x-data class="relative mt-8">
    <div class="flex flex-wrap items-center justify-between gap-x-6 gap-y-2">
        <h2 class="text-heading-400 text-lg font-semibold">
            Prodotti della collezione
            <span class="text-text-400 text-base font-normal">({{ $products->total() }})</span>
        </h2>

        @if ($products->total() > 1)
            <label class="text-text-400 flex items-center gap-2 text-sm">
                <span class="text-trim">Ordina:</span>

                <x-select
                    wire:model.live="sort"
                    class="bg-surface-400 hover:bg-surface-500 min-w-40 rounded-full px-4 py-2 text-sm font-semibold"
                >
                    @foreach ($sorts as $key => $label)
                        <option value="{{ $key }}" @selected($sort === $key)>{{ $label }}</option>
                    @endforeach
                </x-select>
            </label>
        @endif
    </div>

    @if ($products->isEmpty())
        <div class="py-12 text-center">
            @if ($userIsOwner)
                <p>La tua collezione è ancora vuota. Aggiungi le tue grafiche per iniziare.</p>
                <x-button
                    type="button"
                    size="small"
                    width="auto"
                    class="mt-4"
                    x-on:click="$dispatch('open-modal', { name: 'collection-designs' })"
                >Gestisci grafiche</x-button>
            @else
                <p>Questa collezione non contiene ancora prodotti.</p>
            @endif
        </div>
    @else
        <div
            class="mt-5 grid grid-cols-2 gap-x-4 gap-y-8 transition-opacity sm:columns-[15rem]"
            wire:loading.class="opacity-50"
        >
            @foreach ($products as $product)
                <x-product-card :$product :key="'collection-product-'.$product->id" />
            @endforeach
        </div>

        <div class="mt-10">{{ $products->onEachSide(1)->links('pagination.livewire') }}</div>
    @endif
</section>
