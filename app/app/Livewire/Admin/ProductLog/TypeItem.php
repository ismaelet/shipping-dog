<?php

namespace App\Livewire\Admin\ProductLog;

use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductColor;
use App\Models\Ecommerce\Product\ProductPosition;
use App\Models\Ecommerce\Product\ProductSize;
use App\Models\Ecommerce\Product\ProductType;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class TypeItem extends Component {

    /**
     * Property: The order items collection
     */
    public Collection $orderItems;

    /**
     * Property: The product type to filter by
     */
    public ProductType $type;

    /**
     * Property: The product color to filter by
     */
    public ProductColor $color;

    /**
     * Property: The product size to filter by
     */
    public ProductSize $size;

    /**
     * Property: The position (optional)
     */
    public ?ProductPosition $position = null;

    /**
     * Property: The design (optional)
     */
    public ?Design $design = null;

    /**
     * Property: The product (optional)
     */
    public ?Product $product = null;

    /**
     * Property: The count of printed items
     */
    public int $isPrintedCount;

    /**
     * Property: The count of not printed items
     */
    public int $isNotPrintedCount;

    /**
     * Property: The count of items in cart
     */
    public int $isInCartCount;

    /**
     * Property: The count of items not in cart
     */
    public int $isNotInCartCount;

    /**
     * Property: The design (optional)
     */
    public string $category;

    /**
     * Property: The quantity of items
     */
    public int $quantity = 0;

    /**
     * Property: The suffix for special cases (e.g., local delivery)
     */
    public string $suffix = '';

    /**
     * Lifecycle hook: Mount the component
     */
    public function mount(): void {
        $this->category = $this->design ? 'design' : 'product';

        $this->updateCounts();

        $this->quantity = $this->orderItems->sum('quantity');

        if ($this->design) {
            $this->product = Product::where('design_id', $this->design->id)
                ->where('type_id', $this->type->id)
                ->first();
        }
    }

    /**
     * Lifecycle hook: Render the component
     */
    public function render() {
        return view('livewire.admin.product-log.type-item');
    }

    /**
     * Action handler: Handle 'setPrinted' action
     */
    public function setIsPrinted(): void {
        foreach ($this->orderItems as $item) {
            $item->update([
                'is_printed' => (bool) $this->isNotPrintedCount,
            ]);
        }

        $this->updateCounts();

        $this->dispatch(
            'isPrintedUpdated.' .
                ($this->category === 'design' ? 'product' : 'design') .
                '.' .
                $this->type->id .
                '.' .
                $this->color->id .
                '.' .
                $this->size->id,
            [
                'design_id' => $this->design?->id,
            ]
        );
    }

    /**
     * Action handler: Handle 'setInCart' action
     */
    public function setInCart(): void {
        foreach ($this->orderItems as $item) {
            $item->update([
                'is_in_cart' => (bool) $this->isNotInCartCount,
            ]);
        }

        $this->updateCounts();

        $this->dispatch(
            'inCartUpdated.' .
                ($this->category === 'design' ? 'product' : 'design') .
                '.' .
                $this->type->id .
                '.' .
                $this->color->id .
                '.' .
                $this->size->id
        );
    }

    /**
     * Event handler: Handle 'inCartUpdated' event
     */
    #[On('inCartUpdated.{category}.{type.id}.{color.id}.{size.id}')]
    public function onInCartUpdated(): void {
        $this->updateCounts();
    }

    #[On('isPrintedUpdated.{category}.{type.id}.{color.id}.{size.id}')]
    public function onIsPrintedUpdated(array $data): void {
        $this->updateCounts();
    }

    public function updateCounts(): void {
        $this->isPrintedCount = $this->isPrintedCount();
        $this->isNotPrintedCount = $this->isNotPrintedCount();
        $this->isInCartCount = $this->isInCartCount();
        $this->isNotInCartCount = $this->isNotInCartCount();
    }

    public function isPrintedCount(): int {
        return $this->orderItems
            ->where('is_printed', true)
            ->count();
    }

    public function isNotPrintedCount(): int {
        return $this->orderItems
            ->where('is_printed', false)
            ->count();
    }

    public function isInCartCount(): int {
        return $this->orderItems
            ->where('is_in_cart', true)
            ->count();
    }

    public function isNotInCartCount(): int {
        return $this->orderItems
            ->where('is_in_cart', false)
            ->count();
    }
}
