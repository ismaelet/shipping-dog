<x-layouts.seller-dashboard>
  <div class="grid min-w-0 gap-8">
    <header class="grid gap-4 sm:flex sm:items-start sm:justify-between">
      <div class="grid min-w-0 gap-2">
        <x-eyebrow>Prodotto personalizzato</x-eyebrow>
        <x-heading
          variant="section"
          >{{ $product ? 'Modifica: '.$product->name : 'Aggiungi un prodotto' }}</x-heading
        >
        <p class="text-text-400">Gestisci i dettagli, i colori e le immagini del tuo prodotto.</p>
      </div>
      <x-button
        href="{{ route('seller-dashboard.portfolio') }}"
        variant="secondary"
        size="sm"
        width="auto"
        >Torna al portfolio</x-button
      >
    </header>

    @if (session('success'))
      <x-alert type="success" role="status">{{ session('success') }}</x-alert>
    @endif
    @if ($errors->any())
      <x-alert type="error" role="alert"
        >Controlla i campi evidenziati prima di salvare.</x-alert
      >
    @endif
    @if ($product && !$product->status)
      <p class="card border-border-400 border p-4 text-sm">Il prodotto non è ancora pubblico, ma verrà attivato al più presto.</p>
    @endif

    @if ($types->isEmpty())
      <div class="card grid gap-2 p-6">
        <x-heading variant="article"
          >Nessun tipo di prodotto disponibile</x-heading
        >
        <p>Non è possibile aggiungere un prodotto al momento. Riprova più tardi.</p>
      </div>
    @else
      <form
        id="custom-product-form"
        class="grid min-w-0 gap-6"
        method="POST"
        enctype="multipart/form-data"
        action="{{ $product ? route('seller-dashboard.portfolio.products.update', $product) : route('seller-dashboard.portfolio.products.store') }}"
        x-data="customProductEditor(@js($priceReferences), @js($typeOptions), @js($initialTypeId), @js($initialPrice), @js($initialColorIds), @js($initialDefaultColorId))"
        @submit="saving = true"
        @pageshow.window="
          saving = false;
          pendingUploads = 0;
        "
        x-on:livewire-upload-start="startUpload"
        x-on:livewire-upload-finish="finishUpload"
        x-on:livewire-upload-error="finishUpload"
        x-on:livewire-upload-cancel="finishUpload"
      >
        @csrf
        @unless ($product)
          <input type="hidden" name="temp_id" value="{{ $tempId }}" />
        @endunless
        <x-seller-dashboard.product.section
          title="Dettagli del prodotto"
          description="Scegli un nome e racconta cosa rende speciale il tuo prodotto."
        >
          <x-form.field>
            <x-label for="product-name">Nome</x-label>
            <x-input
              id="product-name"
              name="name"
              :value="old('name', $product?->name)"
              maxlength="255"
              required
              class="min-w-0 w-full"
            />
            @error ('name')
              <x-alert type="error">{{ $message }}</x-alert>
            @enderror
          </x-form.field>
          <x-form.field>
            <x-label for="product-description"
              >Descrizione
              <span class="text-text-400 font-normal"
                >(opzionale)</span
              ></x-label
            >
            <x-textarea
              id="product-description"
              name="description"
              maxlength="500"
              class="w-full min-w-0"
              placeholder="Descrivi il tuo prodotto"
              >{{ old('description', $product?->descriptionModel?->description) }}</x-textarea
            >
            @error ('description')
              <x-alert type="error">{{ $message }}</x-alert>
            @enderror
          </x-form.field>
        </x-seller-dashboard.product.section>

        <x-seller-dashboard.product.section
          title="Tipo e prezzo"
          description="Imposta il prezzo di vendita. Il guadagno viene calcolato automaticamente."
        >
          <div class="grid min-w-0 gap-6 md:grid-cols-2">
            <x-form.field>
              <x-label for="product-type">Tipo di prodotto</x-label>
              <x-select
                id="product-type"
                name="type_id"
                x-model.number="typeId"
                ::disabled="uploading"
                @change="changeType"
                required
                class="w-full min-w-0"
              >
                @foreach ($types as $type)
                  <option
                    value="{{ $type->id }}"
                    @selected ($type->id === $initialTypeId)
                  >
                    {{ $type->name }}
                  </option>
                @endforeach
              </x-select>
              @error ('type_id')
                <x-alert type="error">{{ $message }}</x-alert>
              @enderror
            </x-form.field>
            <x-form.field>
              <x-label for="product-price">Prezzo di vendita (€)</x-label>
              <x-input
                id="product-price"
                type="number"
                name="price"
                x-model="price"
                @input="calculate"
                @blur="normalizePrice"
                ::min="minimumPrice"
                step="0.01"
                required
                class="min-w-0 w-full"
              />
              <p class="text-text-400 text-sm">Prezzo minimo: <span x-text="minimumPrice.toFixed(
                    2,
                  )"></span> €</p>
              @error ('price')
                <x-alert type="error">{{ $message }}</x-alert>
              @enderror
            </x-form.field>
          </div>
          <dl class="bg-surface-500 grid gap-4 rounded-lg p-4 sm:grid-cols-2">
            <div>
              <dt class="text-text-400 text-sm">Guadagno capi chiari</dt>
              <dd class="text-heading-400 mt-1 text-xl font-semibold">
                <span x-text="gainW">0.00</span> €
              </dd>
            </div>
            <div>
              <dt class="text-text-400 text-sm">Guadagno capi scuri</dt>
              <dd class="text-heading-400 mt-1 text-xl font-semibold">
                <span x-text="gainC">0.00</span> €
              </dd>
            </div>
          </dl>
        </x-seller-dashboard.product.section>

        <x-seller-dashboard.product.section
          title="Colori disponibili"
          description="Seleziona i colori acquistabili e quello da mostrare nell’anteprima."
        >
          <fieldset class="grid gap-3">
            <legend class="text-heading-400 mb-3 font-semibold">
              Colori attivi
            </legend>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
              <template x-for="color in colors" :key="color.id">
                <label
                  class="border-border-400 has-checked:border-primary-400 focus-within:outline-primary-400 flex cursor-pointer items-center gap-3 rounded-lg border p-3 focus-within:outline-2"
                >
                  <input
                    type="checkbox"
                    name="colors[]"
                    :value="color.id"
                    x-model.number="activeColorIds"
                    :disabled="uploading"
                    @change="changeColors"
                    class="sr-only"
                  />
                  <span
                    class="border-border-400 size-7 shrink-0 rounded-full border"
                    :style="{
                      background: `linear-gradient(135deg, ${color.primary} 50%, ${color.secondary} 50%)`,
                    }"
                  ></span>
                  <span x-text="color.name"></span>
                </label>
              </template>
            </div>
            <p
              x-show="colors.length === 0"
              class="text-text-400 text-sm"
            >Nessun colore disponibile per questo tipo di prodotto.</p>
            @error ('colors')
              <x-alert type="error">{{ $message }}</x-alert>
            @enderror
            @error ('colors.*')
              <x-alert type="error">{{ $message }}</x-alert>
            @enderror
          </fieldset>
          <x-form.field>
            <x-label for="product-default-color">Colore predefinito</x-label>
            <x-select
              id="product-default-color"
              name="default-color-id"
              x-model.number="defaultColorId"
              required
              class="w-full"
            >
              <option value="0" disabled>Seleziona un colore attivo</option>
              <template x-for="color in activeColors" :key="color.id"
                ><option :value="color.id" x-text="color.name"></option
              ></template>
            </x-select>
            @error ('default-color-id')
              <x-alert type="error">{{ $message }}</x-alert>
            @enderror
          </x-form.field>
        </x-seller-dashboard.product.section>

        <x-seller-dashboard.product.section
          title="Immagini del prodotto"
          description="Carica una foto per ogni colore e posizione. Le immagini vengono caricate subito; salva per confermare i dettagli e le varianti."
        >
          <livewire:portfolio.product.images
            :product-id="$product?->id"
            :$tempId
            :type-id="$initialTypeId"
            :color-ids="$initialColorIds"
          />
          @error ('images')
            <x-alert type="error">{{ $message }}</x-alert>
          @enderror
        </x-seller-dashboard.product.section>

        <x-seller-dashboard.product.section
          title="Immagini aggiuntive"
          description="Aggiungi una copertina accanto alla descrizione e altre foto del prodotto (opzionali)."
        >
          <x-form.field>
            <x-label for="product-cover">Immagine di copertina</x-label>
            @if ($coverImage)
              <img
                src="{{ $coverImage }}"
                alt="Copertina attuale del prodotto"
                class="max-h-64 w-full rounded-lg object-contain"
              />
            @endif
            <x-input
              id="product-cover"
              type="file"
              name="cover-image"
              accept="image/jpeg,image/png,image/webp"
              class="min-w-0 w-full"
            />
            <p class="text-text-400 text-sm">JPG, PNG o WebP, massimo 5 MB.</p>
            @error ('cover-image')
              <x-alert type="error">{{ $message }}</x-alert>
            @enderror
          </x-form.field>
          <x-form.field>
            <x-label for="product-more-images">Altre immagini</x-label>
            @if ($extraImages)
              <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                @foreach ($extraImages as $image)
                  <img
                    src="{{ $image }}"
                    alt="Immagine aggiuntiva del prodotto"
                    class="aspect-square w-full rounded-lg object-cover"
                  />
                @endforeach
              </div>
            @endif
            <x-input
              id="product-more-images"
              type="file"
              name="more-images[]"
              accept="image/jpeg,image/png,image/webp"
              multiple
              class="min-w-0 w-full"
            />
            <p class="text-text-400 text-sm">Fino a 10 immagini per caricamento, massimo 5 MB ciascuna.</p>
            @error ('more-images')
              <x-alert type="error">{{ $message }}</x-alert>
            @enderror
            @error ('more-images.*')
              <x-alert type="error">{{ $message }}</x-alert>
            @enderror
          </x-form.field>
        </x-seller-dashboard.product.section>

        <div class="flex flex-wrap items-center justify-end gap-3">
          <span
            x-show="uploading"
            style="display: none"
            class="text-text-400 text-sm"
            role="status"
            >Attendi il caricamento delle immagini…</span
          >
          <x-button type="submit" ::disabled="uploading || saving"
            ><x-icon-save /><span
              x-text="saving ? 'Salvataggio…' : @js($product ? 'Salva modifiche' : 'Aggiungi prodotto')"
              >{{ $product ? 'Salva modifiche' : 'Aggiungi prodotto' }}</span
            ></x-button
          >
        </div>
      </form>
    @endif
    @if ($product)
      <form
        method="POST"
        action="{{ route('seller-dashboard.portfolio.products.delete', $product) }}"
        class="border-border-400 flex justify-end border-t pt-6"
      >
        @csrf
        <x-button
          type="submit"
          size="sm"
          variant="secondary"
          color="danger"
          confirmation="Sei sicuro di voler eliminare questo prodotto? Questa azione non può essere annullata."
          ><x-icon-delete />Elimina il prodotto</x-button
        >
      </form>
    @endif
  </div>
</x-layouts.seller-dashboard>
