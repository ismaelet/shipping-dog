@pushOnce('styles')
    <style>
        .product-tab[aria-selected='true'] {
            color: var(--color-heading-400);
            border-color: currentColor;
        }
    </style>
@endpushOnce

<div>
    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($types as $type)
            <article wire:key="design-type-{{ $type->id }}" class="grid content-start gap-3">
                @if ($product = $products->get($type->id))
                    <img
                        src="{{ $previews[$product->id] }}"
                        alt="{{ $type->name }}"
                        @class(['aspect-square w-full rounded-lg object-cover', 'grayscale opacity-50' => ! $product->status])
                    />
                    <x-heading variant="article-small" class="text-center">{{ $type->name }}</x-heading>
                    <div class="flex flex-wrap justify-center gap-2">
                        @if ($product->status)
                            <x-button size="xs" width="auto" wire:click="editProduct({{ $product->id }})"
                                ><x-icon-settings />Modifica</x-button>
                        @endif
                        <x-button
                            size="xs"
                            width="auto"
                            variant="secondary"
                            wire:click="toggleStatus({{ $product->id }})"
                            wire:loading.attr="disabled"
                        >{{ $product->status ? 'Disattiva' : 'Attiva' }}</x-button>
                    </div>
                @else
                    <x-heading variant="article">{{ $type->name }}</x-heading>
                    @if ($type->colors->isNotEmpty() && $type->positions->isNotEmpty())
                        <x-button size="sm" wire:click="createProduct({{ $type->id }})" wire:loading.attr="disabled"
                            ><x-icon-add />Crea prodotto</x-button>
                    @else
                        <p>Configurazione del capo incompleta.</p>
                    @endif
                @endif
            </article>
        @endforeach
    </div>
    <x-modal name="design-product-editor" title="Modifica prodotto" size="xl" :fixed-height="true">
        @if ($selected)
            <div wire:key="editor-{{ $selected->id }}" x-data="{ tab: 'colors' }" class="grid gap-6">
                <div
                    role="tablist"
                    aria-label="Impostazioni prodotto"
                    @keydown.arrow-right.prevent="
                        const buttons = Array.from($el.querySelectorAll('button'));
                        const next = buttons[(buttons.indexOf(document.activeElement) + 1) % buttons.length];
                        next.focus();
                        next.click();
                    "
                    @keydown.arrow-left.prevent="
                        const buttons = Array.from($el.querySelectorAll('button'));
                        const next =
                            buttons[(buttons.indexOf(document.activeElement) - 1 + buttons.length) % buttons.length];
                        next.focus();
                        next.click();
                    "
                    class="flex flex-wrap gap-2"
                >
                    <button
                        type="button"
                        role="tab"
                        id="product-colors-tab-{{ $selected->id }}"
                        class="product-tab hover:text-heading-400 cursor-pointer border-b border-transparent p-2 transition-colors"
                        aria-controls="product-colors-panel-{{ $selected->id }}"
                        :tabindex="tab === 'colors' ? 0 : -1"
                        :aria-selected="tab === 'colors'"
                        @click="tab = 'colors'"
                    >
                        Colori
                    </button>
                    @foreach ($selected->type->positions->where('editable', true) as $position)
                        <button
                            type="button"
                            role="tab"
                            id="product-position-tab-{{ $selected->id }}-{{ $position->id }}"
                            class="product-tab hover:text-heading-400 cursor-pointer border-b border-transparent p-2 transition-colors"
                            aria-controls="product-position-panel-{{ $selected->id }}-{{ $position->id }}"
                            :tabindex="tab === 'position-{{ $position->id }}' ? 0 : -1"
                            :aria-selected="tab === 'position-{{ $position->id }}'"
                            @click="tab = 'position-{{ $position->id }}'"
                        >
                            {{ $position->name }}
                        </button>
                    @endforeach
                </div>
                <div
                    x-show="tab === 'colors'"
                    role="tabpanel"
                    id="product-colors-panel-{{ $selected->id }}"
                    aria-labelledby="product-colors-tab-{{ $selected->id }}"
                >
                    <livewire:ecommerce.designs.edit.product-colors
                        :product="$selected"
                        :position-id="$selected->position_id"
                        :key="'colors-'.$selected->id"
                    />
                </div>
                @foreach ($selected->type->positions->where('editable', true) as $position)
                    <div
                        x-show="tab === 'position-{{ $position->id }}'"
                        role="tabpanel"
                        id="product-position-panel-{{ $selected->id }}-{{ $position->id }}"
                        aria-labelledby="product-position-tab-{{ $selected->id }}-{{ $position->id }}"
                    >
                        <livewire:ecommerce.designs.edit.product-position
                            :product="$selected"
                            :position-id="$position->id"
                            :key="'position-'.$selected->id.'-'.$position->id"
                        />
                    </div>
                @endforeach
            </div>
        @endif
    </x-modal>
</div>
