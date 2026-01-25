<?php

namespace App\Livewire\Product\Options;

use App\Models\Ecommerce\Product;
use Livewire\Component;

class Color extends Component {

    /**
     * Property: The product instance
     */
    public Product $product;

    /**
     * Property: The selected color ID
     */
    public int $selectedColorId;

    /**
     * Lifecycle method: Mount the component
     */
    public function mount(Product $product) {
        $this->product = $product;
        $this->selectedColorId = $product->color_id;
    }

    /**
     * lifecycle method: Render the component view
     */
    public function render() {
        return view('livewire.product.options.color');
    }

    public function updatedSelectedColorId(): void {
        $this->dispatch('change-color', $this->selectedColorId);
    }
}
