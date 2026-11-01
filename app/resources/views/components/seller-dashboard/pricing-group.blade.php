@props([
    'title',
    'items',
    'hasColorGains' => false,
    'accent' => 'primary',
])

<section class="border-border-400 bg-surface-400 overflow-hidden rounded-xl border">
    <h2 @class([
        'border-b px-4 py-3 font-bold',
        'border-primary-400 bg-primary-400/10 text-primary-300' => $accent === 'primary',
        'border-detail-1-400 bg-detail-1-400/10 text-detail-1-400' => $accent === 'detail-1',
    ])>
        {{ $title }}
    </h2>

    <div class="overflow-x-auto">
        <table class="w-full min-w-xl table-fixed border-collapse text-left">
            <colgroup>
                <col class="{{ $hasColorGains ? 'w-[31%]' : 'w-[40%]' }}" />
                <col class="{{ $hasColorGains ? 'w-[21%]' : 'w-[24%]' }}" />
                <col class="{{ $hasColorGains ? 'w-[24%]' : 'w-[36%]' }}" />
                @if ($hasColorGains)
                    <col class="w-[24%]" />
                @endif
            </colgroup>
            <thead class="bg-background-300/50">
                <tr class="text-xs font-bold tracking-[0.08em] uppercase">
                    <th scope="col" class="px-3 py-3 text-left sm:px-4">Articolo</th>
                    <th scope="col" class="px-2 py-3 text-center">Prezzo</th>
                    <th scope="col" class="px-2 py-3 text-center">
                        {{ $hasColorGains ? 'Guadagno bianchi' : 'Guadagno' }}
                    </th>
                    @if ($hasColorGains)
                        <th scope="col" class="px-2 py-3 text-center">Guadagno colorati</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-border-400 divide-y">
                @foreach ($items as $item)
                    <tr
                        class="hover:bg-surface-500/50 transition-colors"
                        data-base-white="{{ $item['baseWhite'] }}"
                        data-base-color="{{ $item['baseColor'] }}"
                    >
                        <th
                            scope="row"
                            class="text-heading-400 px-3 py-3 text-sm leading-5 font-semibold sm:px-4 sm:text-base"
                        >
                            {{ $item['name'] }}
                        </th>
                        <td class="px-2 py-2">
                            <label class="sr-only" for="price-{{ $item['tag'] }}">Prezzo {{ $item['name'] }}</label>
                            <div class="border-border-400 bg-background-400 focus-within:border-primary-400 focus-within:ring-primary-400 mx-auto flex max-w-24 items-center rounded-lg border px-2 focus-within:ring-1">
                                <span aria-hidden="true" class="text-text-300 text-xs">€</span>
                                <input
                                    id="price-{{ $item['tag'] }}"
                                    type="number"
                                    class="price-inp text-heading-400 w-full min-w-0 appearance-none bg-transparent px-1 py-1.5 text-right outline-none"
                                    name="price_{{ $item['tag'] }}"
                                    value="{{ number_format($item['price'], 2, '.', '') }}"
                                    min="{{ $item['minimum'] }}"
                                    data-min="{{ $item['minimum'] }}"
                                    step="0.01"
                                    inputmode="decimal"
                                    required
                                />
                            </div>
                        </td>
                        <td class="text-success-400 px-2 py-3 text-center text-sm font-bold whitespace-nowrap sm:text-base">
                            <span class="gain-white">{{ number_format($item['gainWhite'], 2, '.', '') }} €</span>
                        </td>
                        @if ($hasColorGains)
                            <td class="text-success-400 px-2 py-3 text-center text-sm font-bold whitespace-nowrap sm:text-base">
                                <span class="gain-color">{{ number_format($item['gainColor'], 2, '.', '') }} €</span>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
