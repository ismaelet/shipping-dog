<x-layouts.admin-dashboard>
    <x-dashboard.typography.main-heading class=" mb-12">Genera le etichette</x-dashboard.typography.main-heading>

    <x-dashboard.form.panel title="Etichette da generare">

        <form class="grid gap-12" method="POST">
            @csrf
            @session('success')
                <x-form.group>
                    <x-inputs.alert-success>{{ $value }}</x-inputs.alert-success>
                </x-form.group>
            @endsession

            <x-form.group>
                <x-inputs.label>Scarica dall'ordine, all'ordine:</x-inputs.label>
                <x-inputs.input type="number" name="first_cart_id" value="{{ $firstCart->id ?? '' }}"
                    placeholder="Scarica dall'ordine numero" data-size="2" required />

                <x-inputs.input type="number" name="last_cart_id" value="{{ $lastCart->id ?? '' }}"
                    placeholder="Fino all'ordine numero" data-size="2" required />
            </x-form.group>

            <div class="grid justify-center gap-4">
                <x-inputs.button type="sumit" name="submit" value="generate">
                    {!! embed_icon('download') !!}
                    Scarica le etichette
                </x-inputs.button>

                <x-inputs.button type="submit" name="submit" value="process_all" variant="secondary">
                    {!! embed_icon('inventory') !!}
                    Elabora gli ordini
                </x-inputs.button>
            </div>
        </form>

    </x-dashboard.form.panel>
</x-layouts.admin-dashboard>
