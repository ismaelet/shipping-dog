<x-user-dashboard.settings-section
    title="Immagini profilo"
    text="Personalizza il tuo account con un'immagine profilo e una copertina."
    icon="image"
    tone="detail-2"
>
    @session('success')
        <x-alert type="success" class="mb-5"> {{ session('success') }} </x-alert>
        type="success">
    @endsession

    <div class="grid gap-6">
        {{-- Profile image --}}
        <div class="bg-background-400/60 flex flex-wrap items-center gap-5 rounded-2xl p-5">
            <div class="size-24 shrink-0 overflow-hidden rounded-full">
                <livewire:upload-image
                    :url="$user->iconPath()"
                    :size="512"
                    :quality="90"
                    aspect="square"
                    :key="'user-icon-'.$user->id"
                />
            </div>

            <div class="min-w-0 flex-1">
                <p class="text-heading-400 text-sm font-bold">Immagine profilo</p>
                <p class="text-text-400 text-xs">JPG, PNG o WEBP. Consigliata almeno 150 × 150 px.</p>
            </div>
        </div>

        {{-- Cover image --}}
        <div class="border-border-400 grid gap-4 border-t border-dashed pt-6">
            <div>
                <p class="text-heading-400 text-sm font-bold">Immagine di copertina</p>
                <p class="text-text-400 text-xs">JPG, PNG o WEBP. Consigliata almeno 1600 × 600 px.</p>
            </div>

            <div class="bg-background-400/60 grid min-h-37.5 grid-cols-1 overflow-hidden rounded-2xl">
                <livewire:upload-image
                    :url="$user->coverPath()"
                    :size="1600"
                    :quality="90"
                    aspect="cover"
                    :key="'user-cover-'.$user->id"
                />
            </div>
        </div>
    </div>
</x-user-dashboard.settings-section>
