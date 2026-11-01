@php
    $totalSales = collect($salesByMonth)->sum('salesCount');
@endphp

<section class="card rounded-3xl p-8 shadow-sm">
    <header class="border-border-400 flex items-end justify-between gap-4 border-b pb-6">
        <div>
            <x-eyebrow class="">{{ $selectedYear }}</x-eyebrow>
            <x-heading variant="article" class="mt-2">Cronologia vendite</x-heading>
            <p class="mt-2 text-sm">Le vendite del {{ $selectedYear }} divise per mese</p>
        </div>
        <p class="text-sm">{{ $totalSales }} {{ $totalSales === 1 ? 'vendita totale' : 'vendite totali' }}</p>
    </header>

    <div>
        @foreach ($salesByMonth as $data)
            <details class="group border-border-400 border-b py-2" @if ($loop->first) open @endif>
                <summary class="[&::-webkit-details-marker]:hidden flex cursor-pointer list-none items-center justify-between py-5">
                    <span class="flex flex-wrap items-center gap-x-3 gap-y-1">
                        <span class="font-heading text-heading-400 text-lg font-semibold">
                            {{ $data['month'] }} {{ $selectedYear }}
                        </span>
                        <span class="bg-primary-400 h-6 w-0.5" aria-hidden="true"></span>
                        <span class="text-primary-200 font-bold">{{ $data['gain'] }}</span>
                        <span class="text-sm">
                            da {{ $data['salesCount'] }} {{ $data['salesCount'] === 1 ? 'vendita' : 'vendite' }}
                        </span>
                    </span>
                    <x-icon-keyboard-arrow-right
                        class="size-4 shrink-0 transition-transform group-open:rotate-90"
                        aria-hidden="true"
                    />
                </summary>

                <div class="border-border-400 mb-5 overflow-x-auto rounded-2xl border">
                    <table class="w-full min-w-170 border-collapse text-left text-sm">
                        <thead class="bg-background-400/60 text-text-400 text-xs tracking-wider uppercase">
                            <tr>
                                <th scope="col" class="px-5 py-3 font-bold">Ordine</th>
                                <th scope="col" class="px-5 py-3 font-bold">Design</th>
                                <th scope="col" class="px-5 py-3 font-bold">Guadagno</th>
                                <th scope="col" class="px-5 py-3 font-bold">Prodotto</th>
                                <th scope="col" class="px-5 py-3 font-bold">Data</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($data['orderItems'] as $orderItem)
                                <tr class="border-border-400 border-b last:border-b-0">
                                    <td class="text-heading-400 px-5 py-4 font-bold">{{ $orderItem->idCode() }}</td>
                                    <td class="text-heading-400 px-5 py-4">
                                        @if ($orderItem->quantity > 1)
                                            {{ $orderItem->quantity }} x
                                        @endif
                                        {{ $orderItem->product ? $orderItem->product->name : '- design rimosso -' }}
                                    </td>
                                    <td class="text-heading-400 px-5 py-4 font-bold">
                                        {{ price($orderItem->total_seller_earnings_after_reduction) }}
                                    </td>
                                    <td class="px-5 py-4">
                                        {{ $orderItem->product?->type?->name }}
                                        @if ($orderItem->color?->name)
                                            <span class="text-text-300">· {{ $orderItem->color->name }}</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        {{ \Illuminate\Support\Carbon::parse($orderItem->created_at)->translatedFormat('j M Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </details>
        @endforeach
    </div>

    @if (count($yearsWithSales) > 1)
        <nav aria-label="Anno" class="flex flex-wrap justify-center gap-2 pt-8">
            @foreach ($yearsWithSales as $year)
                <button
                    type="button"
                    wire:click="showYear({{ $year }})"
                    @class([
                        'rounded-full px-4 py-2 text-sm font-bold transition-colors',
                        'bg-primary-400 text-white' => $year == $selectedYear,
                        'bg-background-400 text-heading-400 hover:bg-surface-500' => $year != $selectedYear,
                    ])
                    @if ($year == $selectedYear) aria-current="page" @endif
                >
                    {{ $year }}
                </button>
            @endforeach
        </nav>
    @endif
</section>
