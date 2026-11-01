@use('App\Helpers\EcommerceHelper')

@pushOnce('styles')
    <style>
        .card {
            display: grid;
            gap: 1rem;
            padding: 1.5rem;
        }
    </style>
@endpushOnce

<x-layouts.main>
    <div class="bg-surface-500 full-width relative justify-items-center py-20 text-center">
        <img
            class="fill-width absolute h-full w-full object-cover opacity-70"
            src="{{ Vite::image('auth/register-background.webp') }}"
        />

        <x-heading variant="section" class="relative"
            >Ordine <span class="text-primary">{{ $order->idCode() }}</span></x-heading>
    </div>

    <div class="mt-12 mb-20 grid gap-12">
        <div class="status-bar flex items-center justify-center gap-4" style="color: {{ $order->statusColor() }}">
            <span class="status-icon grid aspect-square w-12 place-items-center rounded-full border">
                <x-dynamic-component :component="'icon-'.$order->statusIcon()" class="w-1/2" />
            </span>

            <x-heading variant="article" class="text-current">{{ $order->statusName() }}</x-heading>
        </div>

        @if (in_array($order->status, [EcommerceHelper::statusFromTag('received')]))
            <form
                class="grid justify-items-center gap-6"
                method="POST"
                action="{{ route('orders.destroy', [$order, 'token' => $order->token]) }}"
            >
                @csrf
                @method('DELETE')

                <p class="text-center">
                    Siamo in attesa della conferma del pagamento. Se hai già pagato, per favore contattaci a
                    <span class="text-link"
                        ><a
                            href="{{ config('custom.contacts.email.url') }}"
                            >{{ config('custom.contacts.email.label') }}</a
                        ></span>
                    e provvederemo a sbloccare l'ordine.<br /><br />Se invece non hai ancora completato il pagamento,
                    puoi farlo qui, oppure puoi cancellare l'ordine se hai cambiato idea:
                </p>

                @session('error')
                    <x-alert type="error"> {{ session('error') }} </x-alert>
                @endsession

                <div class="flex flex-wrap justify-center gap-4">
                    <x-button href="{{ $order->paymentUrl() }}" class="grow sm:grow-0">
                        <x-icon-credit-card />
                        Procedi al pagamento
                    </x-button>

                    <x-button type="submit" variant="secondary" color="error" class="grow sm:grow-0">
                        <x-icon-close />
                        Cancella l'ordine
                    </x-button>
                </div>
            </form>
        @elseif (in_array($order->status, [
            EcommerceHelper::statusFromTag('confirmed'),
            EcommerceHelper::statusFromTag('awaiting-shipping'),
            EcommerceHelper::statusFromTag('confirmed'),
            EcommerceHelper::statusFromTag('shipped'),
        ]))
            <p class="mx-auto w-full max-w-200 text-center">
                Il tuo ordine verrà spedito entro
                <span class="text-heading-400 font-bold"
                    >{{ $order->shipping_method->minimumWorkingDays($order->hasLongDelivery()) . '-' . $order->shipping_method->maximumWorkingDays($order->hasLongDelivery()) }} giorni
                    lavorativi</span>
                dal momento della conferma. Riceverai una email dal
                <span class="text-heading-400 font-bold">corriere GLS</span> con codice e istruzioni di
                <span class="text-heading-400 font-bold">tracciamento</span> intorno alle
                <span class="text-heading-400 font-bold">20:00</span> del giorno di spedizione.<br /><br />Abbiamo
                mandato una email di conferma al tuo indirizzo email. Nel caso non appaia entro 5 minuti dal momento del
                pagamento, consigliamo di controllare la
                <span class="text-heading-400 font-bold">cartella di spam</span>.
            </p>
        @endif

        <div class="grid items-start gap-6 sm:gap-8 lg:grid-cols-2 lg:gap-12">
            <div class="grid gap-6 sm:gap-8">
                {{-- Payment --}}
                <article class="card">
                    <h3 class="">Metodo di pagamento</h3>

                    <x-heading variant="article">{{ $order->payment_method?->name }}</x-heading>
                </article>

                {{-- Shipping --}}
                <article class="card">
                    <h3 class="">Metodo di spedizione</h3>

                    <x-heading variant="article"> {!! $order->shipping_method->name !!} </x-heading>

                    <div>
                        <p>
                            {!! $order->shipping_method->deliveryDateString($order->created_at, $order->hasLongDelivery()) !!}
                        </p>
                        <p class="text-text-300 mt-1 text-sm">{{ $order->shipping_method->desc }}</p>
                    </div>
                </article>

                {{-- Address --}}
                <article class="card">
                    <x-heading variant="article">Indirizzo</x-heading>

                    <p>
                        {!! implode('<br>', $order->address->full_address ?? []) !!}<br />
                        -<br />
                        {!! $order->address?->email ?? '' !!}<br />
                        {!! $order->address?->phone ?? '' !!}
                    </p>
                </article>
            </div>

            <div class="grid gap-6 sm:gap-8">
                <section class="card">
                    <x-heading variant="article">Articoli</x-heading>

                    <livewire:cart.order-items-list :order="$order" />
                </section>

                <section class="card">
                    <x-heading variant="article">Riepilogo</x-heading>

                    <livewire:cart.recap-panel :order="$order" />
                </section>
            </div>
        </div>
    </div>
</x-layouts.main>
