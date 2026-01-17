<?php

namespace App\Livewire;

use App\Models\Ecommerce\Design;
use App\Models\User;
use App\Models\User\Like;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class WishlistButton extends Component {

    public Design $design;

    public ?User $user;

    public ?int $wishlistRecordId = null;

    public bool $wishlisted = false;

    public function mount(): void {
        $this->user = Auth::user();
        $this->wishlistRecordId = $this->user?->wishlist_items()
            ->where('design_id', $this->design->id)
            ->value('id');
        $this->wishlisted = (bool) $this->wishlistRecordId;
    }

    /**
     * Add the design to the wishlist
     */
    public function store(): void {
        $like = Like::create([
            'user_id' => $this->user->id,
            'design_id' => $this->design->id,
        ]);

        $this->wishlistRecordId = $like->id;
        $this->wishlisted = true;
    }

    /**
     * Remove the design from the wishlist
     */
    public function destroy(): void {
        Like::where('user_id', $this->user->id)
            ->where('design_id', $this->design->id)
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
}
