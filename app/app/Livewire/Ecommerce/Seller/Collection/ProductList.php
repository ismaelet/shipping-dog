<?php

namespace App\Livewire\Ecommerce\Seller\Collection;

use App\Models\Ecommerce\Design\DesignCollection;
use App\Models\Ecommerce\Seller;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public const SORTS = [
        'popular' => 'Popolari',
        'recent' => 'Nuovi',
        'price_asc' => 'Prezzo crescente',
        'price_desc' => 'Prezzo decrescente',
    ];

    public const PER_PAGE = 24;

    #[Locked]
    public Seller $seller;

    #[Locked]
    public DesignCollection $collection;

    #[Locked]
    public bool $userIsOwner = false;

    #[Url]
    public string $sort = 'popular';

    public function mount(): void
    {
        $this->normalizeSort();
    }

    public function updatedSort(): void
    {
        $this->normalizeSort();
        $this->resetPage();
    }

    public function render()
    {
        $this->normalizeSort();

        $products = $this->collection->purchasableProducts()
            ->with('type', 'color', 'position', 'design_with_trashed', 'base_price', 'specific_price', 'seller.prices');

        match ($this->sort) {
            'recent' => $products->orderByDesc('products.created_at'),
            'price_asc', 'price_desc' => $products->orderByRaw(
                '(COALESCE(
                    (SELECT price FROM items_prices WHERE items_prices.product_id = products.id LIMIT 1),
                    (SELECT price FROM prices_sellers WHERE prices_sellers.seller_id = ? AND prices_sellers.type_id = products.type_id LIMIT 1),
                    (SELECT price FROM prices_default WHERE prices_default.type_id = products.type_id LIMIT 1)
                )) '.($this->sort === 'price_asc' ? 'ASC' : 'DESC'),
                [$this->seller->id]
            ),
            default => $products->orderByDesc('products.sales'),
        };

        return view('livewire.ecommerce.seller.collection.product-list', [
            'products' => $products
                ->orderByDesc('products.design_id')
                ->orderBy('products.id')
                ->paginate(self::PER_PAGE),
            'sorts' => self::SORTS,
        ]);
    }

    private function normalizeSort(): void
    {
        if (! array_key_exists($this->sort, self::SORTS)) {
            $this->sort = 'popular';
        }
    }
}
