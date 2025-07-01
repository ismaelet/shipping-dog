<?php

namespace App\Livewire\Cart;

use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\Seller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class CreditPanel extends Component {

	/**
	 * Property: The open order.
	 * 
	 * @var Order
	 */
	public Order $order;

	/**
	 * Property: The seller record of the authenticated user.
	 * 
	 * @var Seller|null
	 */
	public ?Seller $seller = null;

	/**
	 * Property: Whether the credit panel is visible.
	 * 
	 * @var bool
	 */
	public bool $visible = false;

	/**
	 * Property: The total credit used in the order.
	 * 
	 * @var float
	 */
	public float $usedCredit;

	/**
	 * Property: The total credit available to the seller.
	 * 
	 * @var float
	 */
	public float $totalCredit;

	/**
	 * Lifecycle hook: Mount the component.
	 * 
	 * @return void
	 */
	public function mount(): void {
		$this->order = cart();
		$this->seller = Auth::user()?->seller;
		$this->visible = is_seller() && $this->seller?->toBePayed() > 0 && !$this->order->coupon;
		$this->usedCredit = $this->order->usedCredit();
		$this->totalCredit = (float) $this->seller?->toBePayed();
	}

	/**
	 * Lifecycle hook: Render the component view.
	 * 
	 * @return \Illuminate\View\View
	 */
	public function render(): View {
		return view('livewire.cart.credit-panel');
	}

	/**
	 * Event handler: Update the amount of credit used in the order.
	 * 
	 * @return void
	 */
	#[On('usedCreditUpdated')]
	public function usedCreditUpdated(): void {
		$this->usedCredit = $this->order->usedCredit();
		$this->totalCredit = (float) $this->seller->toBePayed();
	}
}
