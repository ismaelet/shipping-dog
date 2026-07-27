<?php

namespace App\Livewire\Portfolio\Product;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class TypeSelect extends Component {

    /**
     * Property: The available product types
     */
    public Collection $types;

    /**
     * Property: The selected product type ID
     */
    public ?int $typeId = null;

    /**
     * Lifecycle method: Mount the component with available types and an optional initial type ID
     */
    public function mount(
        Collection $types,
        ?int $initialTypeId = null,
    ): void {
        $this->types = $types;
        $this->typeId = $initialTypeId;
    }

    /**
     * Lifecycle method: Render the component view
     */
    public function render() {
        return view('livewire.portfolio.product.type-select');
    }

    /**
     * Action method: Handle the update of the selected type ID and dispatch an event
     */
    public function updatedTypeId(int|null $value): void {
        $this->typeId = $value !== null
            ? (int) $value
            : null;

        if ($this->typeId) {
            $this->dispatch(
                'product-type-updated',
                typeId: $this->typeId,
            );
        }
    }
}
