@props(['seller', 'owner' => false, 'collN' => 0, 'collBlock' => 10])

<section class="py-16">
    {{-- Heading --}}
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-primary-400 text-xs font-bold tracking-[0.2em] uppercase">Esplora le collezioni</p>

            <h2 class="text-heading-400 mt-2 text-3xl font-bold tracking-tight">Collezioni</h2>
        </div>

        @if ($owner)
            <x-button
                type="button"
                onclick="document.getElementById('seller-collection-dialog').showModal()"
                size="small"
                variant="secondary"
            >
                Nuova collezione
                <x-icon-add />
            </x-button>
        @endif
    </div>

    {{-- Existing collection cards --}}
    <div class="sort-pan relative" data-type="coll">
        <div
            id="collections-grid"
            class="sortable grid grid-cols-2 items-end gap-4 sm:grid-cols-[repeat(auto-fill,minmax(15rem,1fr))]"
        >
            <x-seller.collections :$seller :visible-collections="$collBlock" />
        </div>

        @if ($collN > $collBlock)
            <input type="hidden" id="collN" value="{{ $collN }}" />
            <input type="hidden" id="collBlock" value="{{ $collBlock }}" />
            <input type="hidden" id="collPos" value="{{ $collBlock }}" />
        @endif

        {{-- Loading state, initially hidden --}}
        <div class="sort-load absolute inset-0 hidden place-items-center bg-white/80 backdrop-saturate-0 dark:bg-black/80">
            <x-icon-loading class="size-20" />
        </div>
    </div>

    {{-- Create collection --}}
    @if ($owner)
        <dialog
            id="seller-collection-dialog"
            class="bg-surface-400 text-heading-400 backdrop:bg-background-300/60 border-border-400 m-auto w-[calc(100%-2rem)] max-w-md rounded-3xl border p-0 shadow-2xl"
        >
            <form
                method="POST"
                action="{{ route('seller.collection.add', ['seller' => $seller]) }}"
                class="grid gap-6 p-6 sm:p-8"
            >
                @csrf

                <div>
                    <h2 class="text-xl font-bold">Aggiungi una collezione</h2>

                    <p class="text-text-400 mt-2 text-sm">Organizza le tue grafiche in una nuova collezione.</p>
                </div>

                <div>
                    <label for="seller-collection-name" class="mb-2 block text-sm font-semibold">
                        Nome della collezione
                    </label>

                    <input
                        id="seller-collection-name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Es. Collezione estiva"
                        maxlength="255"
                        required
                        class="bg-background focus:border-primary-400 w-full rounded-xl border border-neutral-300 px-4 py-3 transition-colors outline-none dark:border-white/15"
                    />

                    @error('name')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3">
                    <x-button type="button" onclick="this.closest('dialog').close()" variant="secondary" size="small">
                        Annulla
                    </x-button>

                    <x-button type="submit" size="small"> Aggiungi </x-button>
                </div>
            </form>
        </dialog>

        @if ($errors->has('name'))
            @pushOnce('scripts')
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        document.getElementById('seller-collection-dialog')?.showModal();
                    });
                </script>
            @endPushOnce
        @endif
    @endif
</section>

@pushOnce('styles')
    <style>
        /* Existing collection cards */

        .sort-pan .coll-ele {
            align-self: end;
            min-width: 0;
        }

        .sort-pan .collection {
            display: block;
            position: relative;
            aspect-ratio: 1;
            overflow: hidden;
            border-radius: 1rem;
            background-color: var(--color-primary-400);
            background-position: center;
            background-size: contain;
            box-shadow: 0 4px 30px rgb(0 0 0 / 15%);
            transition: box-shadow 0.4s;
        }

        .sort-pan .collection:hover {
            box-shadow: 0 4px 30px rgb(0 0 0 / 40%);
        }

        .sort-pan .coll-img {
            transition: transform 0.4s;
        }

        .sort-pan .collection:hover .coll-img {
            transform: scale(1.1);
        }

        .sort-pan .coll-cover {
            opacity: 0;
            background-color: rgb(0 0 0 / 50%);
            transition: opacity 0.4s;
        }

        .sort-pan .collection:hover .coll-cover {
            opacity: 1;
        }

        .sort-pan .coll-title-box {
            position: absolute;
            right: 0;
            bottom: 1.5rem;
            left: 0;
            padding-inline: 1rem;
            text-align: center;
        }

        .sort-pan .coll-title {
            display: inline-block;
            max-width: 100%;
            padding: 0.7rem 1.8rem;
            border-radius: 9999px;
            background-color: rgb(0 0 0 / 50%);
            color: white;
            font-size: 0.9rem;
            letter-spacing: 0.04em;
            backdrop-filter: blur(5px);
            box-shadow: 0 1rem 15px -0.5rem rgb(0 0 0 / 80%);
            transition: color 0.4s;
        }

        .sort-pan .coll-name {
            overflow: hidden;
            padding-block: 1.5rem 0.5rem;
            text-overflow: ellipsis;
            white-space: nowrap;
            letter-spacing: 0.05rem;
        }

        .sort-pan .item-num {
            color: #aaa;
            font-size: 0.9em;
        }

        .sort-pan .coll-add-ico {
            display: inline-block;
            width: 2rem;
            height: 2rem;
        }

        .sort-pan .coll-more-bot {
            margin-top: 1rem;
            border-radius: 9999px;
            box-shadow: 0 30px 20px -20px rgb(0 0 0 / 70%);
            line-height: 3;
            transition: background 0.3s;
        }

        @media (max-width: 639px) {
            .sort-pan .coll-title-box {
                bottom: 1rem;
            }

            .sort-pan .coll-title {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }
        }
    </style>
@endPushOnce
