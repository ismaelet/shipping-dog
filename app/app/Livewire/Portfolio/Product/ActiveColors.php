<?php

namespace App\Livewire\Portfolio\Product;

use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductType;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class ActiveColors extends Component {

    /**
     * Currently selected product type
     */
    public ?ProductType $productType = null;

    /**
     * Property: The product instance
     */
    public ?Product $product = null;

    /**
     * Property: The available colors
     */
    public ?Collection $availableColors = null;

    /**
     * Property: The active colors
     */
    public array $activeColorIds = [];

    /**
     * Lifecycle Method: Mount the component
     */
    public function mount() {
        $this->productType = $this->product?->type ?: ProductType::find(1);

        $this->activeColorIds = $this->product
            ? $this->product->colors->pluck('id')->all()
            : [];

        $this->loadAvailableColors();
    }

    /**
     * Lifecycle Method: Render the component view
     */
    public function render() {
        return view('livewire.portfolio.product.active-colors');
    }

    /**
     * Action method: Toggle the active state of a color for the product
     */
    public function toggleColor(int $colorId) {
        if ($this->product) {
            if (in_array($colorId, $this->activeColorIds)) {
                $this->product->colors()->detach($colorId);

                $this->activeColorIds = array_values(array_diff($this->activeColorIds, [$colorId]));
            } else {
                $this->product->colors()->attach($colorId);

                $this->activeColorIds[] = $colorId;
            }
        } else {
            if (in_array($colorId, $this->activeColorIds)) {
                $this->activeColorIds = array_values(array_diff($this->activeColorIds, [$colorId]));
            } else {
                $this->activeColorIds[] = $colorId;
            }
        }

        $this->dispatch('change-active-colors', ['activeColorIds' => $this->activeColorIds]);
    }

    /**
     * Event listener: Update the product type and adjust active colors accordingly
     */
    #[On('product-type-updated')]
    public function updateProductType(int $typeId): void {
        $this->productType = ProductType::find($typeId);

        $this->loadAvailableColors();

        $availableColorIds = $this->availableColors
            ->pluck('id')
            ->all();

        $this->activeColorIds = array_values(
            array_intersect(
                $this->activeColorIds,
                $availableColorIds,
            ),
        );

        $this->dispatch(
            'change-active-colors',
            ['activeColorIds' => $this->activeColorIds],
        );
    }

    /**
     * Helper method: Load available colors based on the current product type
     */
    private function loadAvailableColors(): void {
        if (!$this->productType) {
            $this->availableColors = null;

            return;
        }

        $this->availableColors = $this->productType?->colors()
            ->orderBy('reorder')
            ->orderBy('id')
            ->get();
    }
}
