<?php

namespace App\Livewire\Cart;

use App\Models\Ecommerce\Coupon;
use App\Models\Ecommerce\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Livewire\Component;

class CouponPanel extends Component {

	public Order $cart;
	public ?Coupon $coupon;
	public string $couponCode = '';

	/**
	 * Initialize the component.
	 *
	 * @return void 
	 */
	public function mount(): void {
		$this->cart = cart();
		$this->coupon = $this->cart?->coupon;
		$this->couponCode = $this->coupon?->code ?? '';
	}

	/**
	 * Reset the coupon code error when the user updates the coupon code input.
	 * 
	 * @return void
	 */
	public function updatedCouponCode(): void {
		$this->resetValidation();
	}

	/**
	 * Apply the coupon code to the cart
	 *
	 * @return void
	 */
	public function save() {
		// Validate the coupon code
		$this->validate([
			'couponCode' => 'required|string|max:255|exists:coupons,code',
		]);

		// Attempt to apply the coupon code
		if ($error = $this->cart->applyCoupon($this->couponCode)) {
			session()->addError('couponCode', $error);
		} else {
			$this->coupon = $this->cart->coupon;

			session()->flash('success', 'Codice sconto applicato con successo!');
		}

		$this->dispatch('orderItemsUpdated');
	}

	/**
	 * Remove the coupon code from the cart.
	 *
	 * @return void
	 */
	public function remove() {
		// Remove the coupon from the cart
		$this->cart->removeCoupon();

		// Reset the coupon property
		$this->coupon = null;

		session()->flash('success', 'Codice sconto rimosso con successo!');

		$this->dispatch('orderItemsUpdated');
	}

	/**
	 * Render the component view.
	 * 
	 * @return \Illuminate\View\View
	 */
	public function render(): View {
		return view('livewire.cart.coupon-panel');
	}
}
