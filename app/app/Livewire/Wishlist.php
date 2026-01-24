<?php

namespace App\Livewire;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Wishlist extends Component {

    /**
     * Property: The wishlist items
     */
    public ?Collection $wishlistItems = null;

    /**
     * Lifecycle Method: Mount the component
     */
    public function mount() {
        $this->wishlistItems = Auth::user()->wishlist_items->load(
            'product',
            'product.color',
            'product.position',
            'product.design_with_trashed',
            'product.type',
            'product.seller'
        );
    }

    /**
     * Lifecycle Method: Render the component view
     */
    public function render() {
        return view('livewire.wishlist');
    }

    /**
     * Event Handler: Delete an item from the wishlist
     */
    public function deleteItem(int $id): void {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $item = $user->wishlist_items()->where('id', $id)->first();

        if ($item) {
            $item->delete();

            $this->wishlistItems = $user->wishlist_items->load(
                'product',
                'product.color',
                'product.position',
                'product.design_with_trashed',
                'product.type',
                'product.seller'
            );
        }
    }
}
