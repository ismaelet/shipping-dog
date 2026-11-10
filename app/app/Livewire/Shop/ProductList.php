<?php

namespace App\Livewire\Shop;

use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Product\ProductGender;
use App\Models\Ecommerce\Product\ProductType;
use App\Models\Ecommerce\Seller;
use App\Queries\Shop\ProductSearchQuery;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url(as: 'type')]
    public ?string $selectedTypeId = null;

    #[Url(as: 'gender')]
    public ?string $selectedGenderId = null;

    #[Url(as: 'group')]
    public ?string $categoryFilter = null;

    #[Url(as: 'design')]
    public ?string $designId = null;

    #[Url]
    public ?string $sort = null;

    public int $pageSize = 36;

    public ?Seller $seller = null;

    public ?ProductType $selectedType = null;

    public ?ProductGender $selectedGender = null;

    public ?string $selectedCategory = null;

    public bool $scrollOnLoad = false;

    public function mount(): void
    {
        if (! request()->has('group') && request()->query('category') === 'm') {
            $this->categoryFilter = 'm';
        }

        $this->scrollOnLoad = $this->hasListParameters();
        $this->resolveFilters();
        $this->resolveSort();
    }

    public function render(ProductSearchQuery $productSearch)
    {
        $this->resolveFilters();

        $sort = ($this->sort === 'custom' && ! $this->seller)
            ? 'popular'
            : ($this->sort ?? 'popular');

        $shouldDiversify =
            $sort === 'popular' &&
            ! $this->selectedTypeId &&
            ! $this->selectedGenderId &&
            ! $this->categoryFilter &&
            ! $this->designId &&
            trim($this->search) === '';

        // Filtered products
        $query = $productSearch->build(
            seller: $this->seller,
            search: trim($this->search),
            typeId: $this->selectedType?->id,
            genderId: $this->selectedGender?->id,
            category: $this->categoryFilter,
            designId: $this->validId($this->designId),
            sort: $sort,
            diversify: $shouldDiversify,
        );

        $products = $query->paginate($this->pageSize);

        // Total products (cached for 10 minutes)
        $cacheKey = $this->seller
            ? "shop.products.total.seller.{$this->seller->getKey()}"
            : 'shop.products.total.all';

        $totalProducts = Cache::remember(
            $cacheKey,
            now()->addMinutes(10),
            fn () => $productSearch->build(
                seller: $this->seller,
                search: '',
                typeId: null,
                genderId: null,
                sort: 'popular',
            )->count(),
        );

        return view('livewire.shop.product-list', [
            'products' => $products,
            'totalProducts' => $totalProducts,
            'productCount' => $products->total(),
            'design' => $this->validId($this->designId) ? Design::find($this->validId($this->designId)) : null,
            'womenTypes' => $this->getCategoryTypes('c', 2),
            'menTypes' => $this->getCategoryTypes('c', 1),
            'unisexTypes' => $this->getCategoryTypes('c'),
            'accessoryTypes' => $this->getCategoryTypes('m'),
        ]);
    }

    #[On('selectType')]
    public function selectType(?int $typeId, ?int $genderId): void
    {
        if (
            (string) $typeId === (string) $this->selectedTypeId &&
            (string) $genderId === (string) $this->selectedGenderId
        ) {
            $typeId = null;
            $genderId = null;
        }

        $this->selectedTypeId = $typeId !== null
            ? (string) $typeId
            : null;

        $this->selectedGenderId = $genderId !== null
            ? (string) $genderId
            : null;

        $this->categoryFilter = null;

        $this->resolveFilters();
        $this->resetPage();
    }

    public function changeSort(string $sort): void
    {
        $allowedSorts = $this->seller
            ? ['new', 'popular', 'custom']
            : ['new', 'popular'];

        if (! in_array($sort, $allowedSorts, true)) {
            return;
        }

        $this->sort = $sort;
        $this->resetPage();
    }

    #[On('clearFilters')]
    public function removeFilters(): void
    {
        $this->search = '';
        $this->selectedTypeId = null;
        $this->selectedGenderId = null;
        $this->categoryFilter = null;
        $this->designId = null;

        $this->resolveFilters();
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    private function hasListParameters(): bool
    {
        return trim($this->search) !== ''
            || $this->selectedTypeId
            || $this->selectedGenderId
            || $this->categoryFilter
            || $this->designId
            || $this->sort
            || request()->has('page');
    }

    private function resolveFilters(): void
    {
        if ($this->categoryFilter !== 'm') {
            $this->categoryFilter = null;
        }

        $typeId = $this->validId($this->selectedTypeId);
        $genderId = $this->validId($this->selectedGenderId);

        $this->selectedType = $typeId
            ? ProductType::find($typeId)
            : null;

        $this->selectedCategory = $this->selectedType?->category;

        $this->selectedGender = $genderId
            ? ProductGender::find($genderId)
            : null;

        if ($this->selectedType && ! $this->selectedGender) {
            $this->selectedGender = $this->selectedType->gender;
        }
    }

    private function resolveSort(): void
    {
        if (! $this->sort && $this->seller) {
            $this->sort = [
                'new',
                'popular',
                'custom',
            ][$this->seller->sort - 1] ?? null;
        }

        $allowedSorts = $this->seller
            ? ['new', 'popular', 'custom']
            : ['new', 'popular'];

        if (! in_array($this->sort, $allowedSorts, true)) {
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

        if ($category === 'c') {
            $query->where('gender_id', $genderId ?? 3);
        }

        // Only show types with at least one active product
        $query->whereHas('products', function ($query) {
            $query->where('status', true);

            if ($this->seller) {
                $query->where('user_id', $this->seller->user_id);
            }
        });

        return $query->orderBy('sort')->get();
    }

    private function validId(?string $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        $id = filter_var(
            $value,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        return $id === false ? null : $id;
    }

    public function applySearch(): void
    {
        $this->resetPage();
    }
}
