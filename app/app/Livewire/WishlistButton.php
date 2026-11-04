<?php

namespace App\Livewire;

use App\Models\Ecommerce\Product;
use App\Models\User\Like;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class WishlistButton extends Component
{
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
    public int|array|null $colorId = null;

    /**
     * Property: The position ID of the product
     */
    public int|array|null $positionId = null;

    /**
     * Lifecycle method: Mount the component
     */
    public function mount(): void
    {
        $this->colorId = $this->product->color_id;
        $this->positionId = $this->product->position_id;

        $this->refreshWishlistState();
    }

    /**
     * Lifecycle method: Render the component
     */
    public function render(): View
    {
        return view('livewire.wishlist-button');
    }

    /**
     * Action: Add the product to the wishlist
     */
    public function store(): void
    {
        $userId = $this->userId();
        $colorId = $this->normalizeId($this->colorId);
        $positionId = $this->normalizeId($this->positionId);

        if (! $userId || ! $colorId || ! $positionId) {
            return;
        }

        $like = Like::create([
            'user_id' => $userId,
            'product_id' => $this->product->id,
            'color_id' => $colorId,
            'position_id' => $positionId,
        ]);

        $this->wishlistRecordId = $like->id;
        $this->wishlisted = true;
    }

    /**
     * Action: Remove the product from the wishlist
     */
    public function destroy(): void
    {
        $userId = $this->userId();
        $colorId = $this->normalizeId($this->colorId);
        $positionId = $this->normalizeId($this->positionId);

        if (! $userId || ! $colorId || ! $positionId) {
            return;
        }

        Like::where('user_id', $userId)
            ->where('product_id', $this->product->id)
            ->where('color_id', $colorId)
            ->where('position_id', $positionId)
            ->delete();

        $this->wishlistRecordId = null;
        $this->wishlisted = false;
    }

    /**
     * Event listener: Update color
     */
    #[On('change-color')]
    public function changeColor(int|string|array|null $id): void
    {
        $this->colorId = $id;
        $this->refreshWishlistState();
    }

    /**
     * Event listener: Update position
     */
    #[On('change-position')]
    public function changePosition(int|string|array|null $id): void
    {
        $this->positionId = $id;
        $this->refreshWishlistState();
    }

    /**
     * Helper method: Refresh the wishlist state based on the current color and position
     */
    private function refreshWishlistState(): void
    {
        $userId = $this->userId();
        $colorId = $this->normalizeId($this->colorId);
        $positionId = $this->normalizeId($this->positionId);

        if (! $userId || ! $colorId || ! $positionId) {
            $this->wishlistRecordId = null;
            $this->wishlisted = false;

            return;
        }

        $this->wishlistRecordId = Like::where('user_id', $userId)
            ->where('product_id', $this->product->id)
            ->where('color_id', $colorId)
            ->where('position_id', $positionId)
            ->value('id');

        $this->wishlisted = (bool) $this->wishlistRecordId;
    }

    /**
     * Helper method: Get the authenticated user ID
     */
    private function userId(): ?int
    {
        return Auth::id();
    }

    /**
     * Helper method: Normalize an ID
     */
    private function normalizeId(int|string|array|null $value): ?int
    {
        if (is_array($value)) {
            $value = $value['id'] ?? null;
        }

        if ($value === null || $value === '') {
            return null;
        }

        return is_numeric($value) ? (int) $value : null;
    }
}
