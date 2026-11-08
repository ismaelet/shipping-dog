<x-layouts.form
    title="Pagamento ordine #{{ $order->id }}"
    method="POST"
    action="{{ route('nexi.orders.store', $order) }}"
>
    <x-slot name="body">
        <p class="text-center">Continua sul sito sicuro Nexi per pagare con carta.</p>
    </x-slot>

    <x-slot name="footer">
        <x-button type="submit">
            <x-icon-credit-card />
            Paga con Nexi
        </x-button>
    </x-slot>
</x-layouts.form>
