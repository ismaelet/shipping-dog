<x-layouts.seller-dashboard :$seller>
    <div class="grid grid-cols-1 items-start gap-6 xl:grid-cols-[minmax(0,0.9fr)_minmax(0,1fr)]">
        <livewire:seller.activation-request :$seller />

        <div class="grid content-start gap-6">
            <form
                class="dashboard-panel border-border-400 bg-surface-400 grid content-start gap-5 rounded-3xl border"
                method="POST"
                action="{{ route('seller-dashboard.settings', $seller) }}"
            >
                @csrf

                <header class="border-border-400 border-b pb-4">
                    <x-eyebrow>Identità</x-eyebrow>
                    <x-heading variant="article">Nome e descrizione</x-heading>
                </header>

                <div class="grid gap-5">
                    <div class="grid gap-2">
                        <x-label for="seller-name">Nome negozio</x-label>
                        <x-input
                            id="seller-name"
                            type="text"
                            name="name"
                            placeholder="Nome del negozio"
                            value="{{ $seller->name }}"
                            maxlength="50"
                            required
                        />
                    </div>

                    <div class="grid gap-2">
                        <x-label for="seller-description">Descrizione <span class="text-text-400 font-normal">(opzionale)</span></x-label>
                        <x-textarea
                            id="seller-description"
                            class=""
                            name="description"
                            placeholder="Racconta il tuo stile, le tue ispirazioni e il tuo progetto..."
                            rows="3"
                            maxlength="1000"
                        >{{ $seller->description }}</x-textarea>
                    </div>

                    <div class="grid gap-2">
                        <x-label for="seller-sort">Ordina prodotti</x-label>
                        <x-select id="seller-sort" name="sort" class="w-full">
                            <option value="1" @selected($seller->sort == 1)>Dal più venduto al meno venduto</option>
                            <option value="2" @selected($seller->sort == 2)>Dal più recente al meno</option>
                            <option value="3" @selected($seller->sort == 3)>Ordine personalizzato</option>
                        </x-select>
                    </div>
                </div>

                @if (session('success_settings'))
                    <p role="status" class="text-success-400 text-sm">{{ session('success_settings') }}</p>
                @endif

                <div>
                    <x-button type="submit" name="edit" size="sm">Salva modifiche</x-button>
                </div>
            </form>

            <form
                class="dashboard-panel border-border-400 bg-surface-400 grid content-start gap-5 rounded-3xl border"
                id="contacts"
                method="POST"
                action="{{ route('seller-dashboard.contacts', $seller) }}"
            >
                @method('PUT')
                @csrf

                <header class="border-border-400 border-b pb-4">
                    <x-eyebrow>Presenza online</x-eyebrow>
                    <x-heading variant="article">Social e contatti</x-heading>
                </header>

                @if (session('success_socials'))
                    <p role="status" class="text-success-400 text-sm">{{ session('success_socials') }}</p>
                @endif

                <div class="grid grid-cols-1 gap-5">
                    <div class="col-span-full grid gap-2">
                        <x-label for="seller-website">Sito web</x-label>
                        <x-input
                            id="seller-website"
                            type="text"
                            name="website"
                            value="{{ $user->website() }}"
                            placeholder="URL del sito web"
                        />
                        @error('website')
                            <p class="text-danger-400 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-full grid gap-2">
                        <x-label for="seller-facebook">Facebook</x-label>
                        <x-input
                            id="seller-facebook"
                            type="text"
                            name="facebook"
                            value="{{ $user->facebook() }}"
                            placeholder="Username o URL completo"
                        />
                        @error('facebook')
                            <p class="text-danger-400 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-2">
                        <x-label for="seller-instagram">Instagram</x-label>
                        <x-input
                            id="seller-instagram"
                            type="text"
                            name="instagram"
                            value="{{ $user->instagram() }}"
                            placeholder="Username o URL completo"
                        />
                        @error('instagram')
                            <p class="text-danger-400 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-2">
                        <x-label for="seller-tiktok">TikTok</x-label>
                        <x-input
                            id="seller-tiktok"
                            type="text"
                            name="tiktok"
                            value="{{ $user->tiktok() }}"
                            placeholder="Username o URL completo"
                        />
                        @error('tiktok')
                            <p class="text-danger-400 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-2">
                        <x-label for="seller-youtube">YouTube</x-label>
                        <x-input
                            id="seller-youtube"
                            type="text"
                            name="youtube"
                            value="{{ $user->youtube() }}"
                            placeholder="Nome canale o URL completo"
                        />
                        @error('youtube')
                            <p class="text-danger-400 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-2">
                        <x-label for="seller-bluesky">Bluesky</x-label>
                        <x-input
                            id="seller-bluesky"
                            type="text"
                            name="bluesky"
                            value="{{ $user->bluesky() }}"
                            placeholder="Handle o URL completo"
                        />
                        @error('bluesky')
                            <p class="text-danger-400 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-full grid gap-2">
                        <x-label for="seller-twitter">X (Twitter)</x-label>
                        <x-input
                            id="seller-twitter"
                            type="text"
                            name="twitter"
                            value="{{ $user->twitter() }}"
                            placeholder="Username o URL completo"
                        />
                        @error('twitter')
                            <p class="text-danger-400 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <x-button type="submit" size="sm" class="seller-dashboard-submit">Salva contatti</x-button>
                </div>
            </form>
        </div>

        {{-- Image editor --}}
        <section class="dashboard-panel border-border-400 bg-surface-400 grid content-start gap-6 rounded-3xl border">
            <header class="border-border-400 border-b pb-4">
                <x-eyebrow>Aspetto</x-eyebrow>
                <x-heading variant="article">Immagini negozio</x-heading>
            </header>

            {{-- Profile image --}}
            <div class="grid justify-items-center gap-4 text-center">
                <x-heading variant="article-small">Immagine di profilo</x-heading>

                <div class="aspect-square w-40 max-w-full overflow-hidden rounded-full">
                    <livewire:upload-image
                        :url="$seller->iconPath()"
                        :size="512"
                        :quality="90"
                        aspect="square"
                        :key="'seller-icon-'.$seller->id"
                    />
                </div>

                <p class="text-text-400 text-sm leading-5">
                    Usa un'immagine quadrata di almeno
                    <strong class="text-highlight">150 × 150 px</strong>. Sarà mostrata nella pagina del negozio e nei
                    tuoi articoli.
                </p>
            </div>

            {{-- Cover image --}}
            <div class="border-border-400 grid gap-4 border-t border-dashed pt-6">
                <x-heading variant="article-small" class="text-center">Immagine di copertina</x-heading>

                <div class="grid min-h-37.5 grid-cols-1 overflow-hidden rounded-2xl">
                    <livewire:upload-image
                        :url="$seller->coverPath()"
                        :size="1600"
                        :quality="90"
                        aspect="cover"
                        :key="'seller-cover-'.$seller->id"
                    />
                </div>

                <p class="text-text-400 text-center text-sm leading-5">
                    Carica una copertina di almeno
                    <strong class="text-highlight">1600 × 600 px</strong>
                    per presentare al meglio il tuo universo creativo.
                </p>
            </div>
        </section>
    </div>
</x-layouts.seller-dashboard>
