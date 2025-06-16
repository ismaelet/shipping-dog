<?php

namespace App\Livewire\Cart;

use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\Seller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class CreditPanel extends Component {

	public Order $order;

	public ?Seller $seller = null;

	public bool $visible = false;

	public float $usedCredit;

	public float $totalCredit;

	/**
	 * Initialize the component.
	 * 
	 * @return void
	 */
	public function mount(): void {
		$this->order = cart();
		$this->seller = Auth::user()->seller;
		$this->visible = is_seller() && $this->seller->toBePayed() && !$this->order->coupon;
		$this->usedCredit = $this->order->usedCredit();
		$this->totalCredit = $this->seller->toBePayed();
	}

	/**
	 * Refresh the credit used when the 'usedCreditUpdated' event is dispatched.
	 * 
	 * @return void
	 */
	#[On('usedCreditUpdated')]
	public function usedCreditUpdated(): void {
		$this->usedCredit = $this->order->usedCredit();
		$this->totalCredit = $this->seller->toBePayed();
	}

	/**
	 * Render the component view.
	 * 
	 * @return \Illuminate\View\View
	 */
	public function render(): View {
		return view('livewire.cart.credit-panel');
	}
}
