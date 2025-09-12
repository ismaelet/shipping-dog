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

	public ?Like $wishlistRecord = null;

	public ?User $user;

	public bool $wishlisted;

	public function mount(): void {
		$this->user = Auth::user();
		$this->wishlistRecord = $this->user?->wishlist_items()->where('design_id', $this->design->id)->first();
		$this->wishlisted = (bool) $this->wishlistRecord;
	}

	/**
	 * Add the design to the wishlist.
	 * 
	 * @return void
	 */
	public function store(): void {
		Like::create([
			'user_id' => $this->user->id,
			'design_id' => $this->design->id,
		]);

		$this->wishlisted = true;
	}

	/**
	 * Remove the design from the wishlist.
	 * 
	 * @return void
	 */
	public function destroy(): void {
		Like::where([
			'user_id' => $this->user->id,
			'design_id' => $this->design->id,
		])->delete();

		$this->wishlisted = false;
	}

	/**
     * Render the component.
     *
     * @return View
     */
    public function render(): View {
		return view('livewire.wishlist-button');
	}
}
