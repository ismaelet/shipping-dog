@php
    $canRequestPayment = ! $seller->request && $seller->toBePayed() >= 31.25;

    $stats = [
        ['label' => 'Guadagno totale', 'value' => $seller->earnedString(), 'hint' => 'da tutte le vendite'],
        ['label' => 'Pagato', 'value' => $seller->payedString(), 'hint' => 'ti è già stato trasferito'],
        [
            'label' => 'Altre trasferenze',
            'value' => $seller->otherTransferAmountString(),
            'hint' => 'credito usato e altri movimenti',
            'info' => 'Qui figura il totale usato per acquistare prodotti dal proprio negozio, usando il credito accumulato, oppure altri pagamenti di natura varia.',
        ],
        ['label' => 'Da pagare', 'value' => $seller->toBePayedString(), 'hint' => 'guadagno accumulato'],
    ];

    $tabs = ['designs' => 'Grafiche', 'time' => 'Cronologia'];
@endphp

<div class="grid grid-cols-1 gap-8">
    <section class="card rounded-3xl p-8 shadow-sm">
        <header class="border-border-400 flex flex-wrap items-end justify-between gap-6 border-b pb-6">
            <div class="max-w-xl">
                <x-eyebrow class="mb-0">Il tuo shop</x-eyebrow>
                <x-heading variant="article" class="mt-2">Lista vendite</x-heading>
                <p class="mt-3 text-sm">
                    Monitora i guadagni generati dai tuoi design e richiedi il pagamento del saldo disponibile.
                </p>
            </div>

            @if ($canRequestPayment || $seller->request)
                <x-button href="{{ route('seller-dashboard.payment') }}" name="request-payment" size="sm">
                    <x-icon-credit-card />
                    {{ $seller->request ? 'Scarica di nuovo il PDF' : 'Richiedi pagamento' }}
                </x-button>
            @else
                <x-button type="button" disabled size="sm">
                    <x-icon-credit-card />
                    Richiedi pagamento
                </x-button>
            @endif
        </header>

        <div class="grid grid-cols-1 gap-4 pt-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($stats as $stat)
                <div class="bg-background-400/60 rounded-2xl p-5">
                    <div class="flex h-5 items-center gap-2">
                        <span class="text-xs font-bold tracking-wider uppercase">{{ $stat['label'] }}</span>
                        @isset($stat['info'])
                            <x-help.info>{{ $stat['info'] }}</x-help.info>
                        @endisset
                    </div>
                    <p class="font-heading text-heading-400 pt-2 text-2xl font-semibold">{{ $stat['value'] }}</p>
                    <p class="pt-1 text-xs">{{ $stat['hint'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="pt-6 text-sm">
            @if ($seller->request)
                <p>
                    Hai già richiesto il pagamento. Se non hai ancora mandato il documento compilato a
                    <a
                        class="text-link"
                        href="{{ config('custom.contacts.email.url') }}"
                    >{{ config('custom.contacts.email.label') }}</a>
                    ti consigliamo di farlo al più presto. Puoi riscaricare o rigenerare il <strong>PDF</strong> da
                    compilare con il pulsante in alto.
                </p>
            @elseif ($canRequestPayment)
                <p>
                    Se <span class="text-highlight">non hai una partita IVA e risiedi in Italia</span> puoi richiedere
                    il pagamento di <span class="text-highlight">{{ $seller->toBePayedString() }}</span> con il pulsante
                    in alto. Se invece possiedi una partita IVA oppure non risiedi in Italia, contattaci a
                    <a
                        class="text-link"
                        href="{{ config('custom.contacts.email.url') }}"
                        >{{ config('custom.contacts.email.label') }}</a
                    >.
                </p>
            @else
                <p>
                    I pagamenti non sono inviati automaticamente, ma devono essere richiesti. È possibile richiederli se
                    il totale a pagare è superiore a <strong>25,00€ netti</strong> (31,25€ lordi).
                </p>
            @endif
        </div>

        <nav aria-label="Visualizzazione vendite" class="pt-6">
            <div class="border-border-400 inline-flex gap-1 rounded-full border p-1">
                @foreach ($tabs as $key => $label)
                    <button
                        type="button"
                        wire:click="showSort('{{ $key }}')"
                        @class([
                            'rounded-full px-5 py-2.5 text-sm font-bold transition-colors ',
                            'bg-primary-400 text-white ' => $sort === $key,
                            'text-text-400 hover:text-heading-400 cursor-pointer' => $sort !== $key,
                        ])
                        @if ($sort === $key) aria-current="page" @endif
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </nav>
    </section>

    @if ($sales->count())
        @if ($sort === 'designs')
            <x-sales.items :sales="$sales" />
        @else
            <x-sales.history :sales="$sales" :selected-year="$year" />
        @endif
    @else
        <p class="py-8 text-center">Ancora non hai realizzato nessuna vendita</p>
    @endif
</div>
