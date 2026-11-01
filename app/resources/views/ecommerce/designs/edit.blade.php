<x-layouts.main :$title>
  <div class="mb-20 mt-20 grid grid-cols-1 gap-16">
    {{-- Name --}}
    <livewire:ecommerce.designs.edit.name :$design />

    {{-- Design data --}}
    <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-7 lg:gap-16">
      {{-- Left group --}}
      <section class="grid min-w-0 gap-8 lg:col-span-3">
        {{-- Light version --}}
        <x-ecommerce.designs.edit.image-upload
          :$design
          variant="light"
          :exists="$hasLightVariant"
        />

        @if (!$design->custom)
          {{-- Dark version --}}
          <x-ecommerce.designs.edit.image-upload
            :$design
            variant="dark"
            :exists="$hasDarkVariant"
          />

          {{-- Vector --}}
          @include ('ecommerce.designs.edit.image-vector')
        @endif
      </section>

      {{-- Right group --}}
      <div class="grid min-w-0 grid-cols-1 gap-8 lg:col-span-4">
        {{-- Tags --}}
        <livewire:ecommerce.designs.edit.tags :$design />

        {{-- Colors --}}
        @if (!$design->custom)
          <livewire:ecommerce.designs.edit.background-color :$design />

          <livewire:ecommerce.designs.edit.default-color :$design />
        @endif
      </div>
    </div>

    {{-- Product list --}}
    <section class="grid grid-cols-1 gap-8">
      <div class="grid gap-2 text-center">
        <x-heading variant="section">Prodotti collegati</x-heading>

        <x-typography.section-subheading>
          Qui puoi modificare il colore dei capi e la dimensione di stampa nei
          prodotti con questa grafica.</x-typography.section-subheading
        >
      </div>

      <div class="stack-content grid">
        {{-- Products --}}
        <livewire:ecommerce.designs.edit.product-list :$design />
      </div>
    </section>

    {{-- Delete button --}}
    <form
      method="POST"
      action="{{ route('designs.destroy', $design) }}"
      class="flex justify-center"
    >
      @csrf
      @method ('DELETE')
      <x-button
        type="submit"
        variant="secondary"
        color="danger"
        confirmation="Sei sicuro di voler eliminare definitivamente la grafica e tutti i prodotti associati?"
        ><x-icon-delete />Elimina la grafica</x-button
      >
    </form>
  </div>
</x-layouts.main>
