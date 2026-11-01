<x-layouts.form :$title :$location size="lg">
    <x-slot name="body">
        <p class="text-center">Seleziona uno o più articoli da cambiare/restiturire:</p>

        <div class="order-list grid gap-4 sm:grid-cols-[10rem_1fr_1fr]">
            @foreach ($order->order_items as $orderItem)
                @include('support.refund.partials.select_item', ['orderItem' => $orderItem])
            @endforeach
        </div>

        @session('error')
            <x-alert type="error">{{ session('error') }}</x-alert>
        @endsession

        <p class="mt-8">
            In caso di cambio taglia, il costo di questa procedura corrisponde al costo della nuova spedizione ovvero
            <span class="text-highlight">8,00€</span> e cliccando su "Richiedi", verrai reindirizzato/a su PayPal per
            effettuare il pagamento.<br /><br />In caso di reso potrai restituire l'articolo con il metodo di spedizione
            che preferisci e non verrai reindirizzato/a su PayPal.
        </p>
    </x-slot>

    <x-slot name="footer">
        <x-button type="submit">Richiedi</x-button>
        <p>
            <button
                type="submit"
                formaction="{{ route('support.refund.cancel', $changeRequest) }}"
                class="text-danger-400 hover:text-danger-300 cursor-pointer transition-colors"
            >
                Cancella cambio/reso
            </button>
        </p>
    </x-slot>
</x-layouts.form>
