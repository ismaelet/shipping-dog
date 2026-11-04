@pushOnce('scripts')
    <script defer>
        document.addEventListener('DOMContentLoaded', function () {
            function updatePrice(input) {
                const row = input.closest('[data-base-white]');
                let price = input.value ? parseFloat(input.value) : 0;

                if (!price || price < 0) {
                    price = 0.01;
                }

                const gainWhite = Math.max((price - parseFloat(row.dataset.baseWhite)) * 0.75, 0);
                const gainColor = Math.max((price - parseFloat(row.dataset.baseColor)) * 0.75, 0);

                row.querySelector('.gain-white').textContent = `${gainWhite.toFixed(2)} €`;
                row.querySelector('.gain-color')?.replaceChildren(`${gainColor.toFixed(2)} €`);
            }

            document.querySelectorAll('.price-inp').forEach((input) => {
                input.addEventListener('input', () => updatePrice(input));
                input.addEventListener('blur', () => {
                    const minimum = parseFloat(input.dataset.min);
                    let price = input.value ? parseFloat(input.value) : 0;

                    if (price < minimum) {
                        price = minimum;
                    }

                    input.value = price.toFixed(2);
                    updatePrice(input);
                });
            });
        });
    </script>
@endPushOnce

<x-layouts.seller-dashboard>
    <div class="card grid gap-6 p-4 sm:p-6 lg:p-8">
        <header class="border-border-400 grid gap-2 border-b pb-5">
            <x-eyebrow>Listino negozio</x-eyebrow>
            <x-heading variant="article">Modifica prezzi</x-heading>
            <p class="text-text-400 text-sm leading-6">
                Imposti il prezzo di vendita. Il tuo guadagno viene ricalcolato automaticamente in base al costo di
                produzione.
            </p>
        </header>

        @if (session('success'))
            <x-alert type="success" role="status">{{ session('success') }}</x-alert>
        @endif

        <form method="POST" class="grid gap-5">
            @csrf

            @foreach ($pricingGroups as $group)
                <x-seller-dashboard.pricing-group
                    :title="$group['title']"
                    :items="$group['items']"
                    :has-color-gains="$group['hasColorGains']"
                    :accent="$group['accent']"
                />
            @endforeach

            <div class="flex justify-center pt-1">
                <x-button type="submit" name="submit" size="sm"> Aggiorna tutti i prezzi </x-button>
            </div>
        </form>
    </div>
</x-layouts.seller-dashboard>
