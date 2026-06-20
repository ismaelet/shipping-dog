<?php

namespace App\Livewire\Portfolio\Product;

use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductColor;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class DefaultColor extends Component {

    /**
     * Property: The product instance
     */
    public ?Product $product = null;

    /**
     * Property: The available colors
     */
    public ?Collection $colors = null;

    /**
     * Property: The selected default color ID
     */
    public ?int $defaultColorId = null;

    /**
     * Lifecycle Method: Mount the component
     */
    public function mount() {
        $this->colors =  $this->product?->colors;
        $this->defaultColorId = $this->product?->color_id;
    }

    /**
     * Lifecycle Method: Render the component view
     */
    public function render() {
        return view('livewire.portfolio.product.default-color');
    }

    public function updatedDefaultColorId(int $value) {
        if ($this->product) {
            $this->product->color_id = $value;
            $this->product->save();
        }
    }

    /**
     * Event Listener: Handle the change of active colors
     */
    #[On('changeActiveColors')]
    public function handleChangeActiveColors(array $payload) {
        $activeColorIds = $payload['activeColorIds'] ?? [];

        $this->colors = ProductColor::whereIn('id', $activeColorIds)->get();

        if (!in_array($this->defaultColorId, $activeColorIds)) {
            $this->defaultColorId = $this->colors->first()?->id ?? null;
        }
    }
}
