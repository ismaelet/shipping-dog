<?php

namespace App\Livewire\Shop;

use App\Models\Ecommerce\Product\ProductGender;
use App\Models\Ecommerce\Product\ProductType;
use App\Models\Ecommerce\Seller;
use App\Queries\Shop\ProductSearchQuery;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component {

    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url(as: 'type')]
    public ?int $selectedTypeId = null;

    #[Url(as: 'gender')]
    public ?int $selectedGenderId = null;

    #[Url]
    public ?string $sort = null;

    public int $pageSize = 40;

    public ?Seller $seller = null;

    public ?ProductType $selectedType = null;

    public ?ProductGender $selectedGender = null;

    public ?string $selectedCategory = null;

    public function mount(): void {
        $this->resolveFilters();
        $this->resolveSort();
    }

    public function render(ProductSearchQuery $productSearch) {
        $this->resolveFilters();

        $query = $productSearch->build(
            seller: $this->seller,
            search: trim($this->search),
            typeId: $this->selectedType?->id,
            genderId: $this->selectedGender?->id,
            sort: $this->sort ?? 'popular',
        );

        $products = $query->paginate($this->pageSize);

        return view('livewire.shop.product-list', [
            'products' => $products,
            'productCount' => $products->total(),
            'womenTypes' => $this->getCategoryTypes('c', 2),
            'menTypes' => $this->getCategoryTypes('c', 1),
            'accessoryTypes' => $this->getCategoryTypes('m'),
        ]);
    }

    #[On('selectType')]
    public function selectType(?int $typeId, ?int $genderId): void {
        if (
            $typeId === $this->selectedTypeId &&
            $genderId === $this->selectedGenderId
        ) {
            $typeId = null;
            $genderId = null;
        }

        $this->selectedTypeId = $typeId;
        $this->selectedGenderId = $genderId;

        $this->resolveFilters();
        $this->resetPage();
    }

    public function changeSort(string $sort): void {
        if (!in_array($sort, ['new', 'popular', 'custom'], true)) {
            return;
        }

        $this->sort = $sort;
        $this->resetPage();
    }

    #[On('clearFilters')]
    public function removeFilters(): void {
        $this->search = '';
        $this->selectedTypeId = null;
        $this->selectedGenderId = null;

        $this->resolveFilters();
        $this->resetPage();
    }

    public function updatedSearch(): void {
        $this->resetPage();
    }

    private function resolveFilters(): void {
        $this->selectedType = $this->selectedTypeId
            ? ProductType::find($this->selectedTypeId)
            : null;

        $this->selectedCategory = $this->selectedType?->category;

        $this->selectedGender = $this->selectedGenderId
            ? ProductGender::find($this->selectedGenderId)
            : null;

        if ($this->selectedType && !$this->selectedGender) {
            $this->selectedGender = $this->selectedType->gender;
        }
    }

    private function resolveSort(): void {
        if (!$this->sort && $this->seller) {
            $this->sort = [
                'new',
                'popular',
                'custom',
            ][$this->seller->sort - 1] ?? null;
        }

        if (!in_array($this->sort, ['new', 'popular', 'custom'], true)) {
            $this->sort = 'popular';
        }
    }

    private function getCategoryTypes(
        string $category,
        ?int $genderId = null,
    ) {
        $query = ProductType::query()
            ->where('category', $category)
            ->where('hidden', false)
            ->doesntHave('main_type');

        if ($category === 'c' && $genderId !== null) {
            $query->where(function ($query) use ($genderId) {
                $query
                    ->whereHas('gender', function ($query) use ($genderId) {
                        $query->whereKey($genderId);
                    })
                    ->orWhere('unisex', true);
            });
        }

        if ($this->seller) {
            $query->whereHas('products', function ($query) {
                $query
                    ->where('user_id', $this->seller->user_id)
                    ->where('status', true);
            });
        }

        return $query->orderBy('sort')->get();
    }

    public function applySearch(): void {
        $this->resetPage();
    }
}
