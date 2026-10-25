<x-user-dashboard :$title>
    <section class="surface border-border-400 bg-surface-400 rounded-2xl border p-5 sm:p-8">
        <x-user-dashboard.panel-header
            title="I tuoi ordini"
            subtitle="Acquisti"
            text="Controlla lo stato delle spedizioni e consulta gli acquisti passati."
            :buttonLabel="$confirmedOrders->total() ? 'Scarica storico' : null"
            :buttonUrl="$confirmedOrders->total() ? route('user-dashboard.orders.download') : null"
        />

        @if ($confirmedOrders->total() > 0)
            <div class="overflow-x-auto">
                <table class="w-full min-w-160 text-left text-sm">
                    <thead>
                        <tr class="text-text-400 text-sm font-semibold tracking-wider uppercase">
                            <th class="py-4 pr-4 font-semibold">Ordine</th>
                            <th class="py-4 pr-4 font-semibold">Articoli</th>
                            <th class="py-4 pr-4 font-semibold">Totale</th>
                            <th class="py-4 pr-4 font-semibold">Stato</th>
                            <th class="py-4 pr-4 font-semibold">Data</th>
                            <th class="w-6"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($confirmedOrders as $order)
                            <x-user-dashboard.order-row :$order />
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">{{ $confirmedOrders->onEachSide(1)->links('pagination.default') }}</div>
        @else
            <p class="text-text-400 py-10 text-center text-sm">Nessun ordine effettuato</p>
        @endif
    </section>
</x-user-dashboard>
