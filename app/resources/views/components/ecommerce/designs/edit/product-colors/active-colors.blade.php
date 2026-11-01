@props (['colors', 'productId'])

<div class="grid gap-2">
  <div class="flex items-center gap-2">
    <x-label text="Colori attivi" />

    <x-help.info direction="center">
      È possibile comprare la tua grafica stampata su capi di questi colori.
      Attiva quelli che ti piacciono e disattiva quelli che non vuoi rendere
      disponibili per l'acquisto.
    </x-help.info>
  </div>

  <div class="flex flex-wrap gap-2">
    @foreach ($colors as $color)
      <x-ecommerce.designs.edit.color-swatch
        :$color
        wire:key="active-color-{{ $productId }}-{{ $color->id }}"
      >
        <input
          type="checkbox"
          class="sr-only"
          aria-label="{{ $color->name }}"
          value="{{ $color->id }}"
          wire:model.live="activeColorIds"
        />
      </x-ecommerce.designs.edit.color-swatch>
    @endforeach
  </div>

  @error ('activeColorIds')
    <p class="text-sm text-danger-400">{{ $message }}</p>
  @enderror

  @error ('activeColorIds.*')
    <p class="text-sm text-danger-400">{{ $message }}</p>
  @enderror
</div>
