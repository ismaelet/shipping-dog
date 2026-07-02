<?php

namespace App\View\Components\Seller;

use App\Models\Ecommerce\Design\DesignCollection;
use App\Models\Ecommerce\Seller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Collections extends Component {

    /**
     * Property: Whether user is the owner of the collections or not
     */
    public bool $owner;

    /**
     * Property: The seller collections
     */
    public Collection $collections;

    /**
     * Create a new component instance
     */
    public function __construct(
        public Seller $seller,
        public int $visibleCollections = 0,
    ) {
        $this->owner = Auth::check() && Auth::user()->id === $seller->user_id;
        $this->visibleCollections = $visibleCollections;
        $this->collections = $this->getCollections();
    }

    /**
     * Get the view / contents that represent the component
     */
    public function render(): View {
        return view('components.seller.collections');
    }

    /**
     * Helper method: Get the collections for the seller
     */
    private function getCollections(): Collection {
        return DesignCollection::query()
            ->where('user_id', $this->seller->user_id)
            ->when(!$this->owner, function ($query) {
                $query->whereHas('designs');
            })
            ->orderBy('position')
            ->orderByDesc('id')
            ->with('mainDesign')
            ->get();
    }
}
