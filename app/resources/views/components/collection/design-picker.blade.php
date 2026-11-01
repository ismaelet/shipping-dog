@props([
    'name',
    'title',
    'action',
    'designs',
    'collectionId',
    'collections' => collect(),
])

@php
    $formId = 'picker-form-'.$name;
    $initial = $designs->where('collection', $collectionId)->pluck('id')->map(fn ($id) => (string) $id)->values();
@endphp

{{-- Toggle designs in/out of a collection. State lives on the wrapper so the modal footer can use it. --}}
<div
    x-data="{
	    initial: @js($initial),
	    selected: @js($initial),
	    query: '',
	    items: @js($designs->map(fn ($design) => ['id' => (string) $design->id, 'name' => $design->name])->values()),
	    matches(name) { return name.toLowerCase().includes(this.query.trim().toLowerCase()) },
	    visible() { return this.items.filter((item) => this.matches(item.name)).map((item) => item.id) },
	    selectAll() { this.selected = [...new Set([...this.selected, ...this.visible()])] },
	    deselectAll() { this.selected = this.selected.filter((id) => ! this.visible().includes(id)) },
	    get changed() { return [...this.initial].sort().join() !== [...this.selected].sort().join() },
	}"
>
    <x-modal :$name :$title size="lg" :open="$errors->has('designs') || $errors->has('designs.*')">
        @if ($designs->isEmpty())
            <p class="py-6 text-center">Non hai ancora nessuna grafica.</p>
            @isset($empty)
                <div class="flex justify-center">{{ $empty }}</div>
            @endisset
        @else
            <form id="{{ $formId }}" method="POST" action="{{ $action }}" class="flex flex-col gap-4">
                @csrf
                @method('PUT')

                <p class="text-sm sm:text-base">
                    Seleziona le grafiche da includere nella collezione, deseleziona quelle da rimuovere (resteranno nel
                    tuo portfolio). Una grafica già presente in un'altra collezione verrà spostata in questa.
                </p>

                @error('designs')
                    <p class="text-danger-400 text-sm">{{ $message }}</p>
                @enderror
                @error('designs.*')
                    <p class="text-danger-400 text-sm">{{ $message }}</p>
                @enderror

                <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                    <x-input
                        type="search"
                        x-model="query"
                        placeholder="Cerca per nome"
                        aria-label="Cerca grafiche per nome"
                        class="input input-style min-w-0 flex-1"
                    />
                    <button
                        type="button"
                        x-on:click="selectAll()"
                        class="text-primary-400 hover:text-primary-500 cursor-pointer text-sm font-semibold"
                    >
                        Seleziona tutte
                    </button>
                    <button
                        type="button"
                        x-on:click="deselectAll()"
                        class="text-danger-400 hover:text-danger-500 cursor-pointer text-sm font-semibold"
                    >
                        Deseleziona tutte
                    </button>
                </div>

                <ul class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4">
                    @foreach ($designs as $design)
                        <li x-show="matches(@js($design->name))">
                            <label class="block cursor-pointer">
                                <input
                                    type="checkbox"
                                    name="designs[]"
                                    value="{{ $design->id }}"
                                    x-model="selected"
                                    class="peer sr-only"
                                />
                                <span class="bg-surface-400 border-border-400 peer-checked:border-primary-400 peer-focus-visible:outline-primary-400 relative block overflow-hidden rounded-lg border-2 transition-colors peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2">
                                    <img
                                        src="{{ $design->image() }}"
                                        alt=""
                                        width="200"
                                        height="200"
                                        loading="lazy"
                                        class="aspect-square w-full object-cover"
                                    />
                                    <span
                                        x-show="selected.includes('{{ $design->id }}')"
                                        style="display: none"
                                        class="bg-primary-400 absolute top-2 right-2 grid size-6 place-items-center rounded-full text-white"
                                        aria-hidden="true"
                                    >✓</span>
                                    <span class="text-heading-400 block truncate px-2 pt-2 text-sm font-medium">{{ $design->name }}</span>
                                    @if ($collections->has($design->collection))
                                        <span class="block truncate px-2 pb-2 text-xs">In «{{ $collections[$design->collection] }}»</span>
                                    @else
                                        <span class="block pb-2"></span>
                                    @endif
                                </span>
                            </label>
                        </li>
                    @endforeach
                </ul>

                <p x-show="! visible().length" style="display: none">Nessuna grafica trovata.</p>
            </form>
        @endif

        <x-slot:footer>
            @if ($designs->isEmpty())
                <x-button type="button" variant="secondary" size="sm" width="auto" x-on:click="$dispatch('close-modal')"
                    >Chiudi</x-button>
            @else
                <p
                    class="mr-auto text-sm"
                    aria-live="polite"
                    x-text="
                        selected.length === 1
                            ? '1 grafica nella collezione'
                            : selected.length + ' grafiche nella collezione'
                    "
                ></p>
                <x-button type="button" variant="secondary" size="sm" width="auto" x-on:click="$dispatch('close-modal')"
                    >Annulla</x-button>
                <x-button
                    type="submit"
                    :form="$formId"
                    size="sm"
                    width="auto"
                    x-bind:disabled="! changed"
                    class="disabled:cursor-not-allowed disabled:opacity-50"
                >Salva</x-button>
            @endif
        </x-slot:footer>
    </x-modal>
</div>
