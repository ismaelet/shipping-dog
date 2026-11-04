<?php

namespace App\Livewire\Admin\ProductLog;

use App\Models\Ecommerce\Product\ProductType;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class TypeGroup extends Component
{
    /**
     * Property: The product type
     */
    public ProductType $productType;

    /**
     * Property: The order items collection
     */
    public Collection $orderItems;

    /**
     * Lifecycle hook: Render the component
     */
    public function render()
    {
        return view('livewire.admin.product-log.type-group');
    }
}
