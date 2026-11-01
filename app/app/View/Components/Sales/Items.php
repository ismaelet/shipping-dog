<?php

namespace App\View\Components\Sales;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\View\Component;

class Items extends Component
{
    /**
     * Property: The sales items data organized by design
     */
    public array $items;

    /**
     * Property: The total sales count
     */
    public int $totalSales;

    /**
     * Lifecycle method: Create a new component instance
     */
    public function __construct(
        ?Collection $sales = null,
    ) {
        $this->items = $this->salesByItem($sales);
        $this->totalSales = collect($this->items)->sum('total_sales');
    }

    /**
     * Lifecycle method: Get the view / contents that represent the component
     */
    public function render(): View|Closure|string
    {
        return view('components.sales.items');
    }

    private function salesByItem(?Collection $sales): array
    {
        $items = collect();

        $sales = $sales->groupBy('design_id');

        $sales->each(function ($salesGroup, $designId) use ($items) {
            $lastSale = $salesGroup->sortByDesc('time')->first();

            $items->push([
                'design_id' => $designId,
                'name' => $lastSale->design_with_trashed?->name ?? '- prodotto eliminato -',
                'image_url' => $lastSale->design_image_url,
                'last_sale_time' => $lastSale->time,
                'total_sales' => $salesGroup->count(),
                'total_revenue' => price($salesGroup->sum('total_seller_earnings_after_reduction')),
                'lastOrderCreatedAtString' => Carbon::parse($lastSale->created_at)->translatedFormat('j M Y'),
                'createdAtString' => $lastSale->design_with_trashed?->created_at
                    ? Carbon::parse($lastSale->design_with_trashed->created_at)->translatedFormat('j M Y')
                    : '-',
            ]);
        });

        $items = $items->sortByDesc('total_sales')->values();

        return $items->toArray();
    }
}
