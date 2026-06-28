<?php

namespace App\Livewire\Product\Options;

use App\Models\Ecommerce\Product;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Color extends Component {

    /**
     * Property: The product instance
     */
    #[Locked]
    public Product $product;

    /**
     * Property: The default color ID
     */
    #[Locked]
    public int $defaultColorId;

    /**
     * Property: The selected color ID
     */
    public int $selectedColorId;

    /**
     * Lifecycle method: Mount the component
     */
    public function mount() {
        $this->selectedColorId = $this->defaultColorId;
    }

    /**
     * lifecycle method: Render the component view
     */
    public function render() {
        return view('livewire.product.options.color');
    }

    public function updatedSelectedColorId(): void {
        $this->dispatch('change-color', id: $this->selectedColorId);
    }
}
