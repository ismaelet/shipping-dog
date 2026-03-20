<?php

namespace App\Livewire\Product\Options;

use App\Models\Ecommerce\Product;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Position extends Component {

    /**
     * Property: The product instance
     */
    public Product $product;

    /**
     * Property: The selected color ID
     */
    public int $selectedPositionId;

    /**
     * Property: The available positions
     */
    public Collection $positions;

    /**
     * Lifecycle method: Mount the component
     */
    public function mount(Product $product) {
        $this->product = $product;
        $this->selectedPositionId = $product?->position->id ?: 0;
        $this->positions = $product->positions();
    }

    /**
     * lifecycle method: Render the component view
     */
    public function render() {
        return view('livewire.product.options.position');
    }

    /**
     * Event listener: Update position
     */
    public function updatedSelectedPositionId(): void {
        $this->dispatch('change-position', id: $this->selectedPositionId);
    }
}
