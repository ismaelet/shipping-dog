<?php

namespace App\Livewire\Ecommerce\Seller\Collection;

use App\Models\Ecommerce\Design\DesignCollection;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class NameDisplay extends Component {

    /**
     * Property: The collection.
     */
    #[Locked]
    public DesignCollection $collection;

    /**
     * Property: The collection name.
     */
    public string $name = '';

    /**
     * Mount the component.
     */
    public function mount(DesignCollection $collection): void {
        $this->collection = $collection;
        $this->name = $collection->name;
    }

    /**
     * Update the displayed collection name.
     */
    #[On('collection-name-updated')]
    public function updateName(string $name): void {
        $this->name = $name;
    }

    /**
     * Render the component.
     */
    public function render() {
        return view('livewire.ecommerce.seller.collection.name-display');
    }
}
