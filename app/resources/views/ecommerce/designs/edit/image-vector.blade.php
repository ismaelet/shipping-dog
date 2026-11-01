<form
    id="upload-image-vector"
    action="{{ route('designs.upload-image', [$design, 'type' => 'vector']) }}"
    method="POST"
    class="card grid gap-4 p-6"
    enctype="multipart/form-data"
    x-data="{ uploading: false }"
    @submit="uploading = true"
    @pageshow.window="uploading = false"
>
    @csrf

    <div>
        <x-heading variant="article" class="flex gap-2">
            Grafica vettoriale

            @if ($hasVector)
                <span class="text-success w-[1em]">
                    <x-icon-correct />
                </span>
            @endif
        </x-heading>

        <div class="flex gap-2">
            @if ($hasVector)
                <div class="grid grid-cols-1 gap-2">
                    <p class="text-success">Hai già caricato il file vettoriale</p>

                    <p>
                        Grazie per aver caricato il vettoriale. Se avessi bisogno di cambiarlo puoi usare il campo qui
                        sotto.
                    </p>
                </div>
            @else
                <p>
                    (opzionale) file vettoriale in formato
                    <span class="text-highlight">PDF</span> o
                    <span class="text-highlight">AI</span>
                </p>

                <x-help.info direction="mobile-left">
                    Oltre al file in PNG che hai già caricato, consigliamo di caricare anche il design in formato
                    <span class="text-highlight">PDF</span> o <span class="text-highlight">AI</span> per la massima
                    qualità di stampa possibile. Se non hai il design in questo formato non preoccuparti, riusciremo
                    comunque a realizare i tuoi prodotti con stampe di alta qualità.
                </x-help.info>
            @endif
        </div>
    </div>

    <x-form.field class="">
        <div x-show="! uploading">
            <x-input
                type="file"
                name="file_vector"
                size="sm"
                class="w-full"
                accept=".pdf,.ai"
                @input="if ($el.files.length) $el.form.requestSubmit();"
            />
        </div>
        <span x-show="uploading" style="display: none" role="status" class="size-8 justify-self-center">
            <x-icon-loading />
            <span class="sr-only">Caricamento in corso…</span>
        </span>

        @error('file_vector')
            <x-alert type="error">{{ $message }}</x-alert>
        @enderror
    </x-form.field>
</form>
