<?php

namespace App\Livewire\Product\Options;

use App\Models\Ecommerce\Product;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Position extends Component
{
    /**
     * Property: The product instance
     */
    public Product $product;

    /**
     * Property: The default position ID
     */
    public int $defaultPositionId;

    /**
     * Property: The selected position ID
     */
    public int $selectedPositionId;

    /**
     * Property: The available positions
     */
    public Collection $positions;

    /**
     * Lifecycle method: Mount the component
     */
    public function mount()
    {
        $this->selectedPositionId = $this->defaultPositionId;
        $this->positions = $this->product->positions();
    }

    /**
     * lifecycle method: Render the component view
     */
    public function render()
    {
        return view('livewire.product.options.position');
    }

    /**
     * Event listener: Update position
     */
    public function updatedSelectedPositionId(): void
    {
        $this->dispatch('change-position', id: $this->selectedPositionId);
    }
}
