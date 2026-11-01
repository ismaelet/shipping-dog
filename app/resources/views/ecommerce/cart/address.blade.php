<x-layouts.cart :title="$title.' | Carrello'">
    <form id="content" method="POST" class="grid grid-cols-1 gap-12 min-[1025px]:grid-cols-[3fr_2fr]">
        @csrf

        <div id="left_panel" class="panel grid grid-cols-1 content-start gap-8">
            <!-- Country -->
            <section class="card">
                <x-heading variant="article">Metodo di spedizione</x-heading>

                <p class="">
                    @if ($cart->hasLongDelivery())
                        Il tuo ordine contiene un capo in
                        <span class="txt-red font-semibold">preordine</span
                        >. Se vuoi un
                        <strong>tempo di consegna minore</strong
                        >, ti consigliamo di selezionare solo magliette, felpe o accessori e con colore bianco, nero o
                        grigio melange.<br

                        />Se invece ti va bene la data di consegna indicata qui sotto, puoi lasciare l'ordine così
                        com'è.<br

                        /><br />
                    @endif

                    La data di consegna prevista comprende, oltre al tempo di spedizione,
                    <span class="text-highlight">1-5 giorni lavorativi</span> di elaborazione dell'ordine.
                </p>

                <!-- Shipping method -->
                <livewire:cart.shipping-methods :$cart :$shippingMethods />
            </section>

            {{-- Shipping address --}}
            <livewire:cart.shipping-address :$cart />
        </div>

        <div id="right_panel" class="panel grid grid-cols-1 content-start gap-8">
            {{-- Order items --}}
            <section id="items" class="card">
                <x-heading variant="article">Articoli</x-heading>

                <livewire:cart.order-items-list :order="$cart" />
            </section>

            {{-- Credit panel --}}
            <livewire:cart.credit-panel />

            {{-- Coupon panel --}}
            <livewire:cart.coupon-panel />

            {{-- Recap panel --}}
            <section id="recap" class="card">
                <x-heading variant="article">Riepilogo</x-heading>

                <livewire:cart.recap-panel />
            </section>
        </div>
    </form>
</x-layouts.cart>
