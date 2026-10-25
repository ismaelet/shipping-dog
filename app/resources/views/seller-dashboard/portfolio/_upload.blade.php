<form
    action="{{ route('designs.store') }}"
    method="POST"
    enctype="multipart/form-data"
    x-data="portfolioUpload({{ $errors->any() ? 2 : 1 }})"
    class="border-border-400 bg-surface-400 rounded-3xl border p-6 sm:p-8"
    @submit="uploading = true"
>
    @csrf

    <div class="border-border-400 grid justify-items-center gap-4 border-b pb-6 text-center">
        <div class="">
            <x-eyebrow>Nuovo design</x-eyebrow>
            <x-heading variant="section-small">Carica una nuova grafica</x-heading>
        </div>
        <p class="">Aggiungi un file pronto per la stampa al tuo portfolio.</p>
    </div>

    <div class="mt-6 grid gap-6">
        <div x-show="step === 1" class="grid gap-6">
            <p
                x-cloak
                x-show="invalid"
                role="alert"
                class="bg-danger-400/10 text-danger-300 rounded-xl px-4 py-3 text-sm"
            >
                Il file deve essere in <strong>PNG</strong>. Il formato <strong x-text="invalid"></strong> non è
                accettato.
            </p>

            <div
                class="border-surface-600 bg-background-400/60 group mx-auto grid min-h-96 w-full max-w-2xl cursor-pointer content-center justify-items-center gap-2 rounded-3xl border-[1.6px] border-dashed px-6 py-10 text-center transition-colors"
                :class="dragging && 'border-primary-400'"
                @click="$refs.file.click()"
                @dragover.prevent="dragging = true"
                @dragleave="dragging = false"
                @drop.prevent="
                    dragging = false;
                    drop($event, $refs.file);
                "
            >
                <span class="bg-primary-400/15 text-primary-400 mb-4 grid size-20 place-items-center rounded-full">
                    <x-icon-arrow-alt class="size-6 transition-transform group-hover:-translate-y-1" />
                </span>
                <h2 class="font-heading text-heading-400 text-2xl font-semibold">Trascina qui il tuo PNG</h2>
                <p class="text-sm">oppure seleziona un file dal dispositivo</p>
                <x-button class="mt-4" size="small">Scegli un file</x-button>
            </div>

            <div class="bg-background-300 mx-auto w-full max-w-2xl rounded-2xl p-6">
                <x-heading variant="article-small">Requisiti del file</x-heading>
                <dl class="mt-5 grid gap-5 sm:grid-cols-3">
                    <div class="grid gap-1">
                        <dt class="text-heading-400 font-bold">Formato PNG</dt>
                        <dd class="text-sm">Usa uno sfondo trasparente nelle aree non stampate.</dd>
                    </div>
                    <div class="grid gap-1">
                        <dt class="text-heading-400 font-bold">Minimo 1000 px</dt>
                        <dd class="text-sm">Il lato più corto deve avere una buona risoluzione.</dd>
                    </div>
                    <div class="grid gap-1">
                        <dt class="text-heading-400 font-bold">Bordi netti</dt>
                        <dd class="text-sm">Evita ombreggiature pensate solo per capi bianchi.</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div x-cloak x-show="step === 2" class="grid gap-6 lg:grid-cols-[minmax(0,20rem)_minmax(0,1fr)]">
            <div class="grid grid-cols-1 content-start gap-3">
                <template x-if="preview">
                    <button
                        type="button"
                        class="border-border-400 bg-background-400 dark group group relative block cursor-pointer overflow-hidden rounded-2xl border"
                        @click="$refs.file.click()"
                        aria-label="Sostituisci la grafica"
                    >
                        <img
                            :src="preview"
                            alt="Anteprima della grafica selezionata"
                            class="aspect-square w-full object-contain"
                        />
                        <span class="upload-preview-overlay group-hover:bg-background-400/50 absolute inset-0 grid place-items-center text-white opacity-0 transition-all group-hover:opacity-100">
                            <x-icon-upload class="size-10 translate-y-4 transition-transform group-hover:translate-y-0" />
                        </span>
                    </button>
                </template>

                <div x-show="! preview" class="grid grid-cols-1 gap-2">
                    <label for="design-file" class="text-heading-400 text-sm font-semibold">File grafica</label>
                </div>

                <div
                    :class="preview
                        ? 'sr-only'
                        : 'bg-background-400 group relative grid h-64 place-items-center rounded-lg'"
                >
                    <input
                        id="design-file"
                        x-ref="file"
                        type="file"
                        name="file"
                        accept=".png,.jpg,.jpeg,.webp"
                        class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                        required
                        @change="pick($refs.file)"
                    />

                    <x-icon-upload class="size-10 transition-transform group-hover:-translate-y-1" />
                </div>

                @error('file')
                    <x-alerts.error>{{ $message }}</x-alerts.error>
                @enderror
            </div>

            <div class="grid content-start gap-5">
                <div class="grid gap-2">
                    <x-label for="design-name">Titolo della grafica</x-label>
                    <x-input
                        id="design-name"
                        name="name"
                        placeholder="Titolo della grafica"
                        value="{{ old('name') }}"
                        maxlength="30"
                        required
                    />
                    @error('name')
                        <x-alerts.error>{{ $message }}</x-alerts.error>
                    @enderror
                </div>

                <div class="grid gap-2">
                    <x-label for="design-tags">Tag <span class="text-text-400 font-normal">(opzionali)</span></x-label>
                    <x-textarea
                        id="design-tags"
                        name="tags"
                        placeholder="Puoi inserire fino a 10 tag separati da virgola. I tag rendono il tuo design più facile da trovare."
                        maxlength="160"
                    >{{ old('tags') }}</x-textarea>
                    @error('tags')
                        <x-alerts.error>{{ $message }}</x-alerts.error>
                    @enderror
                </div>

                <div class="grid gap-2">
                    <x-label for="design-file-vector">File vettoriale <span class="text-text-400 font-normal">(opzionale)</span></x-label>
                    <p class="text-sm">
                        Anche se non è obbligatorio, consigliamo di caricare la grafica anche in formato
                        <strong class="text-highlight">PDF</strong> o <strong class="text-highlight">AI</strong> per la
                        massima qualità di stampa possibile.
                    </p>
                    <x-input id="design-file-vector" type="file" name="file_vector" accept=".pdf,.ai" />
                    @error('file_vector')
                        <x-alerts.error>{{ $message }}</x-alerts.error>
                    @enderror
                </div>

                <div class="flex flex-wrap items-center gap-1">
                    <x-checkbox name="cbx" text="Dichiaro di aver letto e accettato le " :required="true" />
                    <a class="text-link" href='{{ route('support.upload-terms') }}'>condizioni di invio</a>
                </div>

                <div class="border-border-400 flex flex-wrap items-center justify-between gap-4 border-t pt-5">
                    <x-button type="button" variant="secondary" class="grow sm:grow-0" @click="reset($refs.file)">
                        Cambia file
                        <x-icon-change />
                    </x-button>

                    <x-button
                        type="submit"
                        name="submit"
                        class="grow sm:grow-0"
                        x-bind:disabled="uploading"
                        x-bind:aria-busy="uploading"
                    >
                        <span x-show="! uploading"> Carica la grafica </span>

                        <x-icon-upload x-show="! uploading" />

                        <x-icon-loading class="w-8" x-show="uploading" x-cloak />
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</form>
