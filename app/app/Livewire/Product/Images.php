<?php

namespace App\Livewire\Product;

use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductPosition;
use Livewire\Attributes\On;
use Livewire\Component;

class Images extends Component {

    /**
     * Property: The product instance
     */
    public Product $product;

    /**
     * Property: The selected color ID
     */
    public ?int $selectedColorId = null;

    /**
     * Property: The selected position ID
     */
    public ?ProductPosition $selectedPosition = null;

    /**
     * Property: The selected preview position ID
     */
    public ?int $previewPositionId = null;

    /**
     * Property: The image URL
     */
    public string $mainImageUrl = '';

    /**
     * Property: Other image URLs
     */
    public array $imagesUrls = [];

    /**
     * Lifecycle Method: Mount the component
     */
    public function mount() {
        $this->selectedColorId = $this->product->color_id;
        $this->selectedPosition = $this->product->position;
        $this->previewPositionId = $this->product->position_id;
        $this->mainImageUrl = $this->getMainImageUrl();
        $this->imagesUrls = $this->getImagesUrls();
    }

    /**
     * Lifecycle Method: Render the component view
     */
    public function render() {
        return view('livewire.product.images');
    }

    /**
     * Event listener: Update preview position
     */
    public function updatedPreviewPositionId(): void {
        $this->mainImageUrl = $this->getMainImageUrl();
        $this->imagesUrls = $this->getImagesUrls();
    }

    /**
     * Event listener: Update color
     */
    #[On('change-color')]
    public function changeColor(int $id): void {
        $this->selectedColorId = $id;
        $this->mainImageUrl = $this->getMainImageUrl();
    }

    /**
     * Event listener: Update position
     */
    #[On('change-position')]
    public function changePosition(int $id): void {
        $this->selectedPosition = ProductPosition::find($id);
        $this->previewPositionId = $id;
        $this->mainImageUrl = $this->getMainImageUrl();
        $this->imagesUrls = $this->getImagesUrls();
    }

    /**
     * Get main image URL
     */
    public function getMainImageUrl(): string {
        if ($this->selectedPosition->id != $this->previewPositionId) {
            return $this->product->type->rasterizedImage(
                color: $this->selectedColorId,
                position: $this->previewPositionId,
            );
        }

        return $this->product->image(
            imageSize: 1024,
            color: $this->selectedColorId,
            position: $this->previewPositionId
        );
    }

    /**
     * Get image URLs
     */
    public function getImagesUrls(): array {
        $urls = [$this->selectedPosition->id => $this->product->image(
            imageSize: 480,
            color: $this->selectedColorId,
            position: $this->selectedPosition->id
        )];

        if ($this->product->customImages()->count()) {
            return $this->product->customImages()->toArray();
        }

        $positions = $this->product->type->unique_positions;

        foreach ($positions as $position) {
            if ($position->id == $this->selectedPosition->id || $position->id == $this->selectedPosition->base_of) {
                continue;
            }

            $urls[$position->id] = $this->product->type->rasterizedImage(color: $this->selectedColorId, position: $position->id);
        }

        return $urls;
    }
}
