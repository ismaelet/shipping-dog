@php
    $cover = $collection->image(1024);
@endphp

<x-layouts.main :$title :$thumbnail :$location>
    @if ($cover)
        <div class="full-width bg-surface-500 relative h-48 overflow-hidden sm:h-72">
            <img
                src="{{ $cover }}"
                alt=""
                class="fill-width absolute inset-0 size-full object-cover blur-sm"
                aria-hidden="true"
            />
            <img
                src="{{ $cover }}"
                alt="Copertina della collezione {{ $collection->name }}"
                class="absolute h-full w-full object-cover"
            />
        </div>
    @else
        <div class="full-width bg-primary-400 h-32 sm:h-48"></div>
    @endif

    <div x-data class="pt-6 pb-16 md:pt-8">
        <nav aria-label="Breadcrumb" class="text-sm">
            <ol class="flex flex-wrap items-center gap-x-2">
                <li>
                    <a href="{{ $seller->url() }}" class="hover:text-heading-400">{{ $seller->name }}</a>
                </li>
                <li aria-hidden="true">/</li>
                <li aria-current="page" class="text-heading-400">{{ $collection->name }}</li>
            </ol>
        </nav>

        <header class="mt-4 grid grid-cols-1 items-start justify-between gap-x-8 gap-y-4 lg:grid-cols-[1fr_1.5fr]">
            <div class="min-w-0">
                @if ($userIsOwner)
                    <h1 class="sr-only">{{ $collection->name }}</h1>
                    <livewire:ecommerce.seller.collection.name-input :seller="$seller" :collection="$collection" />
                @else
                    <h1 class="text-heading-400 font-heading text-3xl md:text-4xl">{{ $collection->name }}</h1>
                @endif

                <a href="{{ $seller->url() }}" class="mt-3 inline-flex items-center gap-2">
                    <img src="{{ $seller->icon() }}" alt="" loading="lazy" class="size-10 rounded-full object-cover" />
                    <span>di <span class="text-heading-400 font-semibold">{{ $seller->name }}</span></span>
                </a>

                @if ($designCount || $productCount)
                    <p class="mt-2 text-sm">
                        {{ $designCount }} {{ $designCount === 1 ? 'grafica' : 'grafiche' }} · {{ $productCount }} {{ $productCount === 1 ? 'prodotto' : 'prodotti' }}
                    </p>
                @endif
            </div>

            @if ($userIsOwner)
                <div class="flex flex-wrap justify-end gap-2">
                    <x-button
                        type="button"
                        size="small"
                        width="auto"
                        x-on:click="$dispatch('open-modal', { name: 'collection-designs' })"
                    >
                        <x-icon-add />
                        Gestisci grafiche
                    </x-button>

                    <x-button
                        type="button"
                        variant="secondary"
                        size="small"
                        width="auto"
                        x-on:click="$dispatch('open-modal', { name: 'change-cover' })"
                    >
                        <x-icon-gallery />
                        Cambia copertina
                    </x-button>

                    <x-button
                        type="button"
                        variant="secondary"
                        color="danger"
                        size="small"
                        width="auto"
                        x-on:click="$dispatch('open-modal', { name: 'delete-collection' })"
                    >
                        <x-icon-delete />
                        Elimina collezione
                    </x-button>
                </div>
            @endif
        </header>

        <livewire:ecommerce.seller.collection.product-list :$seller :$collection :user-is-owner="$userIsOwner" />
    </div>

    @if ($userIsOwner)
        <x-collection.design-picker
            name="collection-designs"
            title="Grafiche della collezione"
            :action="route('seller.collection.designs.sync', [$seller, $collection])"
            :designs="$designs"
            :collection-id="$collection->id"
            :collections="$otherCollections"
        >
            <x-slot:empty>
                <a
                    href="{{ route('seller-dashboard.portfolio.add') }}"
                    class="text-primary-400 hover:text-primary-500 font-semibold"
                >Carica una nuova grafica</a>
            </x-slot:empty>
        </x-collection.design-picker>

        <x-modal name="change-cover" title="Cambia copertina" :open="$errors->has('image')">
            <form
                id="change-cover-form"
                method="POST"
                action="{{ route('seller.collection.cover.update', [$seller, $collection]) }}"
                enctype="multipart/form-data"
                x-data="{ preview: null, fileName: '' }"
                class="flex flex-col gap-4"
            >
                @csrf

                @if ($cover)
                    <div>
                        <p class="mb-2 text-sm sm:text-base">Copertina attuale</p>
                        <img
                            src="{{ $cover }}"
                            alt="Copertina attuale"
                            class="bg-surface-500 aspect-16/7 w-full rounded-lg object-contain"
                        />
                    </div>
                @endif

                <div x-show="preview" style="display: none">
                    <p class="mb-2 text-sm sm:text-base">Anteprima nuova copertina</p>
                    <img
                        x-bind:src="preview"
                        alt="Anteprima nuova copertina"
                        class="bg-surface-500 aspect-16/7 w-full rounded-lg object-contain"
                    />
                </div>

                <label class="block">
                    <span class="text-heading-400 mb-1 block text-sm font-medium">Nuova immagine (JPG, PNG o WebP, max 8 MB)</span>
                    <input
                        type="file"
                        name="image"
                        accept="image/jpeg,image/png,image/webp"
                        required
                        class="input input-style w-full"
                        x-on:change="
                            const file = $event.target.files[0];
                            fileName = file ? file.name : '';
                            preview = file ? URL.createObjectURL(file) : null;
                        "
                    />
                </label>
                <p x-show="fileName" x-text="fileName" style="display: none" class="truncate text-sm"></p>

                @error('image')
                    <p class="text-danger-400 text-sm">{{ $message }}</p>
                @enderror
            </form>

            <x-slot:footer>
                <x-button
                    type="button"
                    variant="secondary"
                    size="small"
                    width="auto"
                    x-on:click="$dispatch('close-modal')"
                >Annulla</x-button>
                <x-button type="submit" form="change-cover-form" size="small" width="auto">Salva copertina</x-button>
            </x-slot:footer>
        </x-modal>

        <x-modal name="delete-collection" :title="'Eliminare «'.$collection->name.'»?'" size="sm">
            <p>
                La collezione verrà eliminata definitivamente. Le grafiche e i prodotti associati resteranno nel tuo
                portfolio.
            </p>

            <form
                id="delete-collection-form"
                method="POST"
                action="{{ route('seller.collection.delete', [$seller, $collection]) }}"
            >
                @csrf
                @method('DELETE')
            </form>

            <x-slot:footer>
                <x-button
                    type="button"
                    variant="secondary"
                    size="small"
                    width="auto"
                    x-on:click="$dispatch('close-modal')"
                >Annulla</x-button>
                <x-button type="submit" form="delete-collection-form" color="danger" size="small" width="auto"
                    >Elimina collezione</x-button>
            </x-slot:footer>
        </x-modal>
    @endif
</x-layouts.main>
