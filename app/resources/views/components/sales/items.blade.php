<section class="card overflow-hidden rounded-3xl shadow-sm">
    <header class="border-border-400 p-content-padding flex items-end justify-between gap-4 border-b">
        <div>
            <x-eyebrow class="mb-0">Performance</x-eyebrow>
            <x-heading variant="article" class="mt-2">Vendite per grafica</x-heading>
            <p class="mt-2 text-sm">I design ordinati per quantità venduta.</p>
        </div>
        <p class="text-sm">{{ $totalSales }} {{ $totalSales === 1 ? 'vendita totale' : 'vendite totali' }}</p>
    </header>

    <div class="overflow-x-auto">
        <table class="w-full min-w-190 border-collapse text-left text-sm">
            <thead class="bg-background-400/60 text-text-400 text-xs tracking-wider uppercase">
                <tr>
                    <th scope="col" class="px-6 py-4 font-bold">Quantità venduta</th>
                    <th scope="col" class="px-6 py-4 font-bold">Grafica</th>
                    <th scope="col" class="px-6 py-4 font-bold">Guadagno totale</th>
                    <th scope="col" class="px-6 py-4 font-bold">Ultima vendita</th>
                    <th scope="col" class="px-6 py-4 font-bold">Aggiunto il</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($items as $item)
                    <tr class="border-border-400 border-b last:border-b-0">
                        <td class="px-6 py-4">
                            <span class="bg-primary-400/15 text-primary-500 dark:text-primary-200 inline-block min-w-10 rounded-full px-2.5 py-1 text-center text-xs font-bold">{{ $item['total_sales'] }}</span>
                        </td>
                        <td class="text-heading-400 px-6 py-4 font-semibold">{{ $item['name'] }}</td>
                        <td class="text-heading-400 px-6 py-4 font-bold">{{ $item['total_revenue'] }}</td>
                        <td class="px-6 py-4">{{ $item['lastOrderCreatedAtString'] }}</td>
                        <td class="px-6 py-4">{{ $item['createdAtString'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
