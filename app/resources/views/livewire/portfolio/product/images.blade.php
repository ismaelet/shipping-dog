<div class="grid min-w-0 gap-8">
  @if ($colors->isEmpty())
    <p class="text-text-400 text-sm">Seleziona almeno un colore attivo per caricare le immagini.</p>
  @elseif ($positions->isEmpty())
    <p class="text-text-400 text-sm">Nessuna posizione immagine disponibile per questo tipo di prodotto.</p>
  @else
    @foreach ($positions as $position)
      <section
        wire:key="image-position-{{ $typeId }}-{{ $position->id }}"
        class="grid gap-4"
      >
        <x-heading variant="article-small"
          >Immagine {{ strtolower($position->name) }}</x-heading
        >
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
          @foreach ($colors as $color)
            <div
              wire:key="image-{{ $typeId }}-{{ $position->id }}-{{ $color->id }}"
              class="grid min-w-0 content-start gap-3"
            >
              <x-label
                for="image-{{ $position->id }}-{{ $color->id }}"
                >{{ $color->name }}</x-label
              >
              <div
                class="bg-surface-500 grid aspect-square place-items-center overflow-hidden rounded-lg"
              >
                @if ($previews[$position->id][$color->id])
                  <img
                    src="{{ $previews[$position->id][$color->id] }}"
                    alt="{{ $position->name }}: {{ $color->name }}"
                    class="h-full w-full object-contain"
                  />
                @else
                  <span class="text-text-400 text-sm"
                    >Nessuna immagine caricata</span
                  >
                @endif
              </div>
              <div
                wire:loading.remove
                wire:target="uploads.{{ $position->id }}.{{ $color->id }}"
              >
                <x-input
                  id="image-{{ $position->id }}-{{ $color->id }}"
                  type="file"
                  accept="image/jpeg,image/png,image/webp"
                  wire:model="uploads.{{ $position->id }}.{{ $color->id }}"
                  wire:loading.attr="disabled"
                  wire:target="uploads"
                  class="min-w-0 w-full text-sm"
                />
              </div>
              <span
                wire:loading
                wire:target="uploads.{{ $position->id }}.{{ $color->id }}"
                class="size-8 justify-self-center"
                role="status"
                ><x-icon-loading /><span class="sr-only"
                  >Caricamento in corso…</span
                ></span
              >
              @error ('uploads.'.$position->id.'.'.$color->id)
                <x-alert type="error">{{ $message }}</x-alert>
              @enderror
            </div>
          @endforeach
        </div>
      </section>
    @endforeach
    <p class="text-text-400 text-sm">JPG, PNG o WebP, massimo 5 MB per immagine.</p>
  @endif
</div>
