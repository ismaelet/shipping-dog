<?php

namespace App\Livewire;

use App\Models\Ecommerce\Product;
use App\Models\User\Like;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class WishlistButton extends Component {

    /**
     * Property: The product to wishlist
     */
    public Product $product;

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
    #[Locked]
    public ?int $colorId = null;

    /**
     * Property: The position ID of the product
     */
    #[Locked]
    public ?int $positionId = null;

    /**
     * Lifecycle method: Mount the component
     */
    public function mount(): void {
        $this->colorId = $this->product->color_id;
        $this->positionId = $this->product->position_id;

        $this->refreshWishlistState();
    }

    /**
     * Lifecycle method: Render the component
     */
    public function render(): View {
        return view('livewire.wishlist-button');
    }

    /**
     * Action: Add the product to the wishlist
     */
    public function store(): void {
        $userId = $this->userId();

        if (!$userId) {
            return;
        }

        $like = Like::create([
            'user_id' => $userId,
            'product_id' => $this->product->id,
            'color_id' => $this->colorId,
            'position_id' => $this->positionId,
        ]);

        $this->wishlistRecordId = $like->id;
        $this->wishlisted = true;
    }

    /**
     * Action: Remove the product from the wishlist
     */
    public function destroy(): void {
        $userId = $this->userId();

        if (!$userId) {
            return;
        }

        Like::where('user_id', $userId)
            ->where('product_id', $this->product->id)
            ->where('color_id', $this->colorId)
            ->where('position_id', $this->positionId)
            ->delete();

        $this->wishlistRecordId = null;
        $this->wishlisted = false;
    }

    /**
     * Event listener: Update color
     */
    #[On('change-color')]
    public function changeColor(int|string|array|null $id): void {
        $this->colorId = $this->normalizeId($id);
        $this->refreshWishlistState();
    }

    /**
     * Event listener: Update position
     */
    #[On('change-position')]
    public function changePosition(int|string|array|null $id): void {
        $this->positionId = $this->normalizeId($id);
        $this->refreshWishlistState();
    }

    /**
     * Helper method: Refresh the wishlist state based on the current color and position
     */
    private function refreshWishlistState(): void {
        $userId = $this->userId();

        if (!$userId) {
            $this->wishlistRecordId = null;
            $this->wishlisted = false;

            return;
        }

        $this->wishlistRecordId = Like::where('user_id', $userId)
            ->where('product_id', $this->product->id)
            ->where('color_id', $this->colorId)
            ->where('position_id', $this->positionId)
            ->value('id');

        $this->wishlisted = (bool) $this->wishlistRecordId;
    }

    /**
     * Helper method: Get the authenticated user ID
     */
    private function userId(): ?int {
        return Auth::id();
    }

    /**
     * Helper method: Normalize an ID
     */
    private function normalizeId(int|string|array|null $value): ?int {
        if (is_array($value)) {
            $value = $value['id'] ?? null;
        }

        if ($value === null || $value === '') {
            return null;
        }

        return is_numeric($value) ? (int) $value : null;
    }
}
