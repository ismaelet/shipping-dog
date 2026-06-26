<?php

namespace App\Livewire\Portfolio\Product;

use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductColor;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class UploadImage extends Component {

    /**
     * Property: the position of the mockup image
     */
    public int $positionId;

    /**
     * Property: the product being edited
     */
    public ?Product $product = null;

    /**
     * Property: a temporary ID for the product (used when creating a new product)
     */
    public ?string $tempId = null;

    /**
     * Property: the collection of images for the product
     */
    private ?Collection $colors = null;

    /**
     * Property: the array of image data to be uploaded
     */
    public array $images = [];

    /**
     * Lifecycle hook: initialize the component
     */
    public function mount() {
        $this->colors = $this->product?->colors()->orderBy('reorder')->orderBy('id')->get();
        $this->images = $this->genImageData();
    }

    /**
     * Lifecycle hook: render the component
     */
    public function render() {
        return view('livewire.portfolio.product.upload-image');
    }

    /**
     * Event Listener: handle the change of active colors and update the image data accordingly
     */
    #[On('changeActiveColors')]
    public function handleChangeActiveColors(array $payload) {
        $activeColorIds = $payload['activeColorIds'] ?? [];

        $this->colors = ProductColor::whereIn('id', $activeColorIds)->get();

        $this->images = $this->genImageData();
    }

    /**
     * Helper method: generate the image data for the product
     */
    private function genImageData(): array {
        $positionId = $this->positionId;
        $colors = $this->colors?->toArray() ?? [];

        $images = [];

        foreach ($colors as $color) {
            if ($this->product) {
                $basePath = "products/custom/{$this->product->id}-{$color['tag']}-{$positionId}";
            } else {
                $basePath = "products/temp/{$this->tempId}/{$color['tag']}-{$positionId}";
            }

            $images[$color['name']] = [
                'size' => [1024, 400],
                'quality' => [90, 80],
                'url' => [
                    "{$basePath}-1024.webp",
                    "{$basePath}-400.webp",
                ],
            ];
        }

        return $images;
    }
}
