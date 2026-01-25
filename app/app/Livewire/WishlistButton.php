<?php

namespace App\Livewire;

use App\Models\Ecommerce\Product;
use App\Models\User;
use App\Models\User\Like;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class WishlistButton extends Component {

    /**
     * Property: The product to wishlist
     */
    public Product $product;

    /**
     * Property: The authenticated user
     */
    public ?User $user = null;

    /**
     * Property: The wishlist record ID if wishlisted
     */
    public ?int $wishlistRecordId = null;

    /**
     * Property: Whether the product is wishlisted
     */
    public bool $wishlisted = false;

    /**
     * Property: The color ID of the product
     */
    public ?int $colorId = null;

    /**
     * Property: The position ID of the product
     */
    public ?int $positionId = null;

    /**
     * Lifecycle method: Mount the component
     */
    public function mount(): void {
        $this->user = Auth::user();
        $this->colorId = $this->product->color_id;
        $this->positionId = $this->product->position_id;
        $this->wishlistRecordId = $this->user?->wishlist_items()
            ->where('product_id', $this->product->id)
            ->where('color_id', $this->colorId)
            ->where('position_id', $this->positionId)
            ->value('id');
        $this->wishlisted = (bool) $this->wishlistRecordId;
    }

    /**
     * Add the product to the wishlist
     */
    public function store(): void {
        $like = Like::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'color_id' => $this->colorId,
            'position_id' => $this->positionId,
        ]);

        $this->wishlistRecordId = $like->id;
        $this->wishlisted = true;
    }

    /**
     * Remove the product from the wishlist
     */
    public function destroy(): void {
        Like::where('user_id', $this->user->id)
            ->where('product_id', $this->product->id)
            ->where('color_id', $this->colorId)
            ->where('position_id', $this->positionId)
            ->delete();

        $this->wishlistRecordId = null;
        $this->wishlisted = false;
    }

    /**
     * Render the component
     */
    public function render(): View {
        return view('livewire.wishlist-button');
    }

    /**
     * Event listener: Update color
     */
    #[On('change-color')]
    public function changeColor(int $id): void {
        $this->colorId = $id;
        $this->wishlistRecordId = $this->user?->wishlist_items()
            ->where('product_id', $this->product->id)
            ->where('color_id', $this->colorId)
            ->where('position_id', $this->positionId)
            ->value('id');
        $this->wishlisted = (bool) $this->wishlistRecordId;
    }

    /**
     * Event listener: Update position
     */
    #[On('change-position')]
    public function changePosition(int $id): void {
        $this->positionId = $id;
        $this->wishlistRecordId = $this->user?->wishlist_items()
            ->where('product_id', $this->product->id)
            ->where('color_id', $this->colorId)
            ->where('position_id', $this->positionId)
            ->value('id');
        $this->wishlisted = (bool) $this->wishlistRecordId;
    }
}
