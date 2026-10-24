<x-layouts.form :$title :$location method="POST" action="{{ route('orders.find') }}">
    <x-slot:body>
        <div class="grid grid-cols-1 gap-4">
            <x-form.field>
                <x-label text="Indirizzo email" />
                <x-input
                    type="email"
                    variant="surface"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Indirizzo email:"
                    required
                />
            </x-form.field>

            <x-form.field>
                <x-label text="Numero ordine:" />
                <x-input
                    type="text"
                    variant="surface"
                    name="id"
                    value="{{ old('id') }}"
                    placeholder="Numero ordine"
                    required
                />
            </x-form.field>

            @session('error')
                <x-alerts.error> {{ session('error') }} </x-alerts.error>
            @endsession
        </div>

        <p class="mar-t-2">
            Il numero dell'ordine è indicato nella email di conferma ricevuta dal nostro sito e
            <span class="text-neutral-heading font-bold">NON</span> è il numero di ricevuta indicato nell'email ricevuta
            da PayPal. L'indirizzo email deve essere quello inizialmente utilizzato per effettuare l'ordine.
        </p>
    </x-slot:body>

    <x-slot:footer>
        <x-button type="submit">Ricerca</x-button>
    </x-slot:footer>
</x-layouts.form>
