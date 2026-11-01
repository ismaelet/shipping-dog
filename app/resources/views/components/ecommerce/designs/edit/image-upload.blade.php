@props(['design', 'variant', 'exists' => true])
<form
    id="upload-image-{{ $variant }}"
    method="POST"
    action="{{ route('designs.upload-image', [$design, 'type' => $variant]) }}"
    enctype="multipart/form-data"
    class="card grid grid-cols-1 gap-4 p-6"
    x-data="{ color: @js($design->color), uploading: false }"
    @submit="uploading = true"
    @pageshow.window="uploading = false"
    @design-background-preview.window="color = $event.detail.color"
>
    @csrf
    <div>
        <x-heading variant="article">{{ $variant === 'light' ? 'Grafica chiara' : 'Grafica scura' }}</x-heading>
        <div class="flex flex-wrap items-center gap-2">
            <p>
                {{ $variant === 'light' ? 'grafica utilizzata su capi chiari' : '(opzionale) grafica utilizzata su capi scuri' }}
            </p>
            @if ($design->custom)
                <x-help.info>
                    Questa immagine è usata solo nelle sezioni dove compare la grafica senza un mockup. Essendo questo
                    un prodotto particolare i mockup non vengono creati automaticamente.</x-help.info>
            @elseif ($variant === 'dark')
                <x-help.info>
                    Se la tua grafica non è molto visibile sui capi neri, ma ne hai una versione alternativa più chiara,
                    che va bene per i prodotti scuri, puoi caricare qui questa versione, così da non doverle inserire
                    come due grafiche separate.</x-help.info>
            @endif
        </div>
    </div>
    @if ($exists)
        <div
            class="aspect-square border"
            :style="{ backgroundColor: {{ $variant === 'light' ? 'color' : "'var(--color-heading-400)'" }} }"
        >
            <img
                src="{{ storage_asset('designs/'.$design->id.($variant === 'dark' ? '_dark' : '').'.png') }}"
                alt="{{ $variant === 'light' ? 'Grafica chiara' : 'Grafica scura' }}"
                class="h-full w-full object-contain"
            />
        </div>
    @endif
    <label class="grid grid-cols-1 gap-2"
        >{{ $exists ? 'Ricarica grafica' : 'Carica grafica' }}
        <div class="relative">
            <x-input
                type="file"
                class="w-full"
                name="file_{{ $variant }}"
                accept=".jpg,.jpeg,.png,.webp"
                x-bind:disabled="uploading"
                x-bind:class="uploading ? '' : 'cursor-pointer'"
                @change="if ($el.files.length) $el.form.requestSubmit();"
            />

            <span
                x-cloak
                role="status"
                class="bg-surface-500/80 pointer-events-none absolute inset-0 flex size-full items-center justify-center rounded-lg transition-opacity"
                :class="uploading ? 'visible opacity-100' : 'invisible opacity-0'"
            >
                <x-icon-loading class="size-10" />
                <span class="sr-only">Caricamento in corso…</span>
            </span>
        </div>
    </label>
    @error('file_'.$variant)
        <x-alert type="error">{{ $message }}</x-alert>
    @enderror
</form>
