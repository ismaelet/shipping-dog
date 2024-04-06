@php
    use App\Models\Ecommerce\Order;

    $months = ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'];
    $revenues = [];
    $years = [date('Y') - 1, date('Y')];

    foreach ($years as $key => $year) {
        $yearlyRevenue = [];

        $monthlyRevenue = Order::selectRaw('MONTH(created_at) as month, SUM(fullProductPrice) as total_sales')
            ->whereYear('created_at', $year)
            ->where('status_id', '>', 0)
            ->groupBy('month')
            ->orderBy('month')
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
        const themeColor = getComputedStyle(document.documentElement)
            .getPropertyValue('--theme-h');
        const themeSaturation = getComputedStyle(document.documentElement)
            .getPropertyValue('--theme-dark-s');
        const barLightness = parseInt(getComputedStyle(document.documentElement)
            .getPropertyValue('--color-background-l')) + 20;

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
                        backgroundColor: `hsl(${themeColor} ${themeSaturation} ${barLightness}%)`,
                        data: lastYearRevenue,
                        tension: 0.1,
                    }, {
                        label: {{ $years[1] }},
                        backgroundColor: '#fff',
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

<x-admin.dashboard-item title="Panoramica" subtitle="fatturato per mese" class="col-span-4" variant="big">

    <x-slot name="content">
        <canvas id="revenue-graph"></canvas>
    </x-slot>

</x-admin.dashboard-item>
