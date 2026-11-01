@props (['colors', 'productId', 'activeColorIds'])

<div class="grid gap-2">
  <div class="flex items-center gap-2">
    <x-label text="Colore predefinito" />

    <x-help.info direction="center">
      Qui puoi selezionare il colore del capo che sarà mostrato come prima
      scelta in tutte le anteprime.
    </x-help.info>
  </div>

  <div class="flex flex-wrap gap-2">
    @foreach ($colors as $color)
      <x-ecommerce.designs.edit.color-swatch
        :$color
        wire:key="default-color-{{ $productId }}-{{ $color->id }}"
        :class="!in_array($color->id, $activeColorIds, true) ? 'hidden' : ''"
      >
        <input
          type="radio"
          class="sr-only"
          aria-label="{{ $color->name }}"
          name="default-color-{{ $productId }}"
          value="{{ $color->id }}"
          wire:model.live="defaultColorId"
        />
      </x-ecommerce.designs.edit.color-swatch>
    @endforeach
  </div>

  @error ('defaultColorId')
    <p class="text-sm text-danger-400">{{ $message }}</p>
  @enderror
</div>
