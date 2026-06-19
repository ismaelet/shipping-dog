<?php

namespace App\View\Components\Sales;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class History extends Component {

    /**
     * Property: The names of the months in Italian
     */
    private array $monthNames = [
        'Gennaio',
        'Febbraio',
        'Marzo',
        'Aprile',
        'Maggio',
        'Giugno',
        'Luglio',
        'Agosto',
        'Settembre',
        'Ottobre',
        'Novembre',
        'Dicembre'
    ];

    /**
     * Property: The sales data organized by month for the selected year
     */
    public array $salesByMonth;

    /**
     * Property: The years with sales, used to populate the year selection dropdown
     */
    public array $yearsWithSales;

    /**
     * Lifecycle method: Create a new component instance
     */
    public function __construct(
        public ?int $selectedYear = null,
        ?Collection $sales = null,
    ) {
        if (!$this->selectedYear) {
            $this->selectedYear = $sales?->first()->year;
        }

        $this->salesByMonth = $this->salesByMonth($sales, $this->selectedYear);

        $this->yearsWithSales = $this->getYearsWithSales($sales);
    }

    /**
     * Lifecycle method: Get the view / contents that represent the component
     */
    public function render(): View|Closure|string {
        return view('components.sales.history');
    }

    /**
     * Helper method: Organize the sales data by month for the selected year
     */
    private function salesByMonth(
        ?Collection $sales,
        int $selectedYear
    ): array {
        $monthlySales = [];

        for ($month = 12; $month >= 1; $month--) {

            $monthTotGain = $sales?->sum(function ($orderItem) use ($selectedYear, $month) {
                if ($orderItem->year == $selectedYear && $orderItem->month == $month) {
                    return $orderItem->total_seller_earnings_after_reduction;
                }
            });

            $orderItems = $sales?->where('year', $selectedYear)->where('month', $month);

            $monthSalesCount = $orderItems?->count();

            if ($monthSalesCount === 0) {
                continue;
            }

            $monthlySales[] = [
                'month' => $this->monthNames[$month - 1],
                'gain' => price($monthTotGain),
                'salesCount' => $monthSalesCount,
                'orderItems' => $orderItems,
            ];
        }

        return $monthlySales;
    }

    /**
     * Helper method: Get the years with sales from the sales collection
     */
    private function getYearsWithSales(?Collection $sales): array {
        return $sales?->pluck('year')->unique()->sortDesc()->toArray() ?? [];
    }
}
