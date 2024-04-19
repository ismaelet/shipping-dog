@php
    use App\Models\Ecommerce\Order;

    $months = ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'];
    $revenues = [];
    $years = [date('Y') - 1, date('Y')];

    foreach ($years as $key => $year) {
        $yearlyRevenue = [];

        $monthlyRevenue = Order::where('status', '>', 0)
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->selectRaw('MONTH(created_at) as month, SUM(fullProductPrice) as total_sales')
            ->get();

        foreach ($months as $key => $month) {
            $yearlyRevenue[] = round($monthlyRevenue->where('month', $key + 1)->first()->total_sales ?? 0);
        }

        $revenues[] = $yearlyRevenue;
    }
@endphp

@pushOnce('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpushOnce

@pushOnce('scripts')
    <script defer>
        // Get the styling for the graph
        const colorScheme = document.documentElement.getAttribute('data-color-scheme');

        let lastYearColor =
            `hsl(${getComputedStyle(document.documentElement).getPropertyValue('--color-primary-400-hsl')} / 0.5)`;
        let currentYearColor = getComputedStyle(document.documentElement).getPropertyValue('--color-secondary-400');

        if (colorScheme == 'dark') {
            const themeColor = getComputedStyle(document.documentElement).getPropertyValue('--theme-h');
            const themeSaturation = getComputedStyle(document.documentElement).getPropertyValue('--theme-dark-s');
            const barLightness = parseInt(getComputedStyle(document.documentElement).getPropertyValue(
                '--color-background-l')) + 20;

            lastYearColor = `hsl(${themeColor} ${themeSaturation} ${barLightness}%)`;
            currentYearColor = '#fff';
        }

        // Set the data to be used in the graph
        const months = @json($months);
        const lastYearRevenue = @json($revenues[0]);
        const currentYearRevenue = @json($revenues[1]);

        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('revenue-graph').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: {{ $years[0] }},
                        backgroundColor: lastYearColor,
                        data: lastYearRevenue,
                        tension: 0.1,
                    }, {
                        label: {{ $years[1] }},
                        backgroundColor: currentYearColor,
                        data: currentYearRevenue,
                        tension: 0.1,
                    }]
                },
                options: {
                    elements: {
                        bar: {
                            borderRadius: 5,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                        }
                    },
                }
            });
        });
    </script>
@endpushOnce

<x-dashboard.widget title="Panoramica" subtitle="fatturato per mese" class="col-span-4" variant="big">

    <x-slot name="content">
        <canvas id="revenue-graph"></canvas>
    </x-slot>

</x-dashboard.widget>
