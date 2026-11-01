<x-layouts.form :$title :$location>
    <x-slot name="body">
        <p class="">Per effettuare un cambio di taglia o un rimborso compila i campi qui sotto.</p>

        <p>
            * Il numero dell'ordine è indicato nella email di conferma ricevuta dal nostro sito e
            <span class="text-highlight">NON</span> è il numero di ricevuta indicato nell'email ricevuta da PayPal.<br /><br />*
            L'indirizzo email deve essere quello inizialmente utilizzato per effettuare l'ordine.
        </p>

        <div class="grid grid-cols-1 gap-4">
            <x-form.field>
                <x-label text="Indirizzo email" />
                <x-input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Inserisci l'indirizzo email"
                    required
                />
            </x-form.field>

            <x-form.field>
                <x-label text="Numero dell'ordine" />
                <x-input
                    type="text"
                    name="order_id"
                    value="{{ old('order_id') }}"
                    placeholder="Inserisci il numero dell'ordine"
                    required
                />
            </x-form.field>

            @session('error')
                <x-alert type="error" :message="session('error')" />
            @endsession
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-button type="submit">Cerca</x-button>
    </x-slot>
</x-layouts.form>
