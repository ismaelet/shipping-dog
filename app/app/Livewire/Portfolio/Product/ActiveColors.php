<?php

namespace App\Livewire\Portfolio\Product;

use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductColor;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class ActiveColors extends Component {

    /**
     * Property: The product instance
     */
    public ?Product $product = null;

    /**
     * Property: The available colors
     */
    public Collection $availableColors;

    /**
     * Property: The active colors
     */
    public array $activeColorIds = [];

    /**
     * Lifecycle Method: Mount the component
     */
    public function mount() {
        $this->availableColors = ProductColor::orderBy('reorder')->orderBy('id')->get();

        $this->activeColorIds = $this->product
            ? $this->product->colors->pluck('id')->toArray()
            : [];
    }

    /**
     * Lifecycle Method: Render the component view
     */
    public function render() {
        return view('livewire.portfolio.product.active-colors');
    }

    /**
     * Action method: Toggle the active state of a color for the product
     */
    public function toggleColor(int $colorId) {
        if ($this->product) {
            if (in_array($colorId, $this->activeColorIds)) {
                $this->product->colors()->detach($colorId);

                $this->activeColorIds = array_values(array_diff($this->activeColorIds, [$colorId]));
            } else {
                $this->product->colors()->attach($colorId);

                $this->activeColorIds[] = $colorId;
            }
        } else {
            if (in_array($colorId, $this->activeColorIds)) {
                $this->activeColorIds = array_values(array_diff($this->activeColorIds, [$colorId]));
            } else {
                $this->activeColorIds[] = $colorId;
            }
        }

        $this->dispatch('changeActiveColors', ['activeColorIds' => $this->activeColorIds]);
    }
}
