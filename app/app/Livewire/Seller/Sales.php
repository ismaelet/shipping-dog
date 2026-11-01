<?php

namespace App\Livewire\Seller;

use App\Helpers\EcommerceHelper;
use App\Models\Ecommerce\OrderItem;
use App\Models\Ecommerce\Seller;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;

class Sales extends Component
{
    private const SORTS = ['time', 'designs'];

    /**
     * Property: The seller whose sales are listed
     */
    #[Locked]
    public Seller $seller;

    /**
     * Property: The active view, either the history ("time") or the items ("designs")
     */
    #[Url(as: 'sort', history: true)]
    public string $sort = 'time';

    /**
     * Property: The year displayed in the history view
     */
    #[Url(as: 'year', history: true)]
    public ?int $year = null;

    /**
     * Lifecycle hook: Normalize the sort value coming from the URL
     */
    public function mount(): void
    {
        $this->normalizeSort();
    }

    /**
     * Lifecycle hook: Keep the sort value valid when updated from the client
     */
    public function updatedSort(): void
    {
        $this->normalizeSort();
    }

    /**
     * Lifecycle hook: Render the component view
     */
    public function render()
    {
        $sales = OrderItem::where('seller_id', $this->seller->id)
            ->where('status', '>=', EcommerceHelper::statusFromTag('confirmed'))
            ->select('*')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month')
            ->with('product', 'product.type', 'color', 'order', 'design_with_trashed')
            ->latest()
            ->get();

        return view('livewire.seller.sales', compact('sales'));
    }

    /**
     * Action: Switch between the history and the items views
     */
    public function showSort(string $sort): void
    {
        $this->sort = $sort;
        $this->normalizeSort();
    }

    /**
     * Action: Select the year displayed in the history view
     */
    public function showYear(int $year): void
    {
        $this->year = $year;
    }

    private function normalizeSort(): void
    {
        if (! in_array($this->sort, self::SORTS, true)) {
            $this->sort = 'time';
        }
    }
}
