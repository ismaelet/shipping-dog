<?php

namespace App\Models\Ecommerce;

use App\Helpers\EcommerceHelper;
use App\Mail\OrderStatusMail;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class Cart extends Model {

	protected $table = 'carts';
	protected $fillable = ['user_id', 'shipping_method_id', 'country_id', 'payment_method_id', 'code', 'quantity', 'credit_ship', 'shipping', 'status', 'step', 'time', 'courier_notes', 'shipping_notes', 'has_errors', 'created_at', 'updated_at'];

	/***************************
	 * * Relationship instances *
	 ***************************/
	/**
	 * Get the cart's user
	 */
	public function user(): BelongsTo {
		return $this->belongsTo(User::class);
	}

	/**
	 * Get the cart's shipping method
	 */
	public function shipping_method(): BelongsTo {
		return $this->belongsTo(ShippingMethod::class);
	}

	/**
	 * Get the cart's payment method
	 */
	public function payment_method(): BelongsTo {
		return $this->belongsTo(PaymentMethod::class);
	}

	/**
	 * Get the cart's address
	 */
	public function address(): HasOne {
		return $this->hasOne(Address::class);
	}

	/**
	 * Get the cart coupon
	 */
	public function coupon(): HasOne {
		return $this->hasOne(Coupon::class, 'code', 'code');
	}

	/**
	 * Get the change request that generated this cart
	 */
	public function change_request(): HasOne {
		return $this->HasOne(ChangeRequest::class, 'new_cart_id');
	}

	/**
	 * Get the orders in the cart
	 */
	public function orders(): HasMany {
		return $this->hasMany(Order::class);
	}

	/************************
	 * * Utility methods *
	 ************************/
	/**
	 * Generate the id code
	 */
	public function idCode(): string {
		return '#' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
	}

	/**
	 * Status data
	 */
	public function statusCode(): string {
		return config('custom.ecommerce.cart_statuses')[$this->status]['tag'];
	}

	public function statusName(): string {
		return config('custom.ecommerce.cart_statuses')[$this->status]['label'];
	}

	public function statusColor(): string {
		return config('custom.ecommerce.cart_statuses')[$this->status]['color'];
	}

	public function statusIcon(): string {
		return config('custom.ecommerce.cart_statuses')[$this->status]['icon'];
	}

	public function statusTag(): string {
		return '<span class="px-4 py-1 gap-2 text-background rounded whitespace-nowrap" style="--icon-size: 1em; background-color: ' . $this->statusColor() . ';">' . embed_icon($this->statusIcon()) . ' ' . $this->statusName() . '</span>';
	}

	/**
	 * Get the cart's price
	 */
	public function price(): float {
		return $this->updatePrice()->price;
	}

	public function orderPrice(): float {
		$orderPrice = 0;

		foreach ($this->orders as $order) {
			$orderPrice += $order->price();
		}

		return $orderPrice;
	}

	public function grossOrderPrice(): float {
		$grossOrderPrice = 0;

		foreach ($this->orders as $order) {
			$grossOrderPrice += $order->grossPrice();
		}

		return $grossOrderPrice;
	}

	public function grossShippingPrice(): float {
		return $this->shipping_method ? $this->shipping_method->price($this->orders->sum('quantity')) : 0;
	}

	public function shippingPrice(): float {
		return $this->credit_ship ? 0 : $this->grossShippingPrice();
	}

	public function orderDiscount(): float {
		$orderDiscount = 0;

		foreach ($this->orders as $order) {
			$orderDiscount += $order->discount();
		}

		return $orderDiscount;
	}

	public function shippingDiscount(): float {
		return $this->credit_ship ? $this->grossShippingPrice() : 0;
	}

	public function discount(): float {
		return $this->orderDiscount() + $this->shippingDiscount();
	}

	public function shippingPriceString(): string {
		return EcommerceHelper::formatPrice($this->shippingPrice());
	}

	public function priceString(): string {
		return EcommerceHelper::formatPrice($this->price());
	}

	public function grossOrderPriceString(): string {
		return EcommerceHelper::formatPrice($this->grossOrderPrice());
	}

	public function orderDiscountString(): string {
		return EcommerceHelper::formatPrice($this->orderDiscount());
	}

	public function discountString(): string {
		return EcommerceHelper::formatPrice($this->discount());
	}

	/**
	 * Check if the cart has long delivery times
	 */
	public function hasLongDelivery(): bool {
		foreach ($this->orders as $order) {
			if ($order->product && ($order->product->type?->preorder || $order->color?->preorder)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get a string with the cart's orders
	 */
	public function ordersString(): string {
		$ordersString = '';

		foreach ($this->orders as $order) {
			$ordersString .= $order->quantity . 'x ' . $order->fullName() . '<br>';
		}

		return $ordersString;
	}

	/**
	 * Get the cart's recap page url
	 */
	public function recapUrl(): string {
		return '/order?id=' . $this->id . '&customerId=' . $this->user_id;
	}

	/**
	 * Get the cart's time
	 */
	public function timeString(): string {
		return date('d/m/Y', strtotime($this->time));
	}

	/************************
	 * * Actions *
	 ************************/
	/**
	 * Recheck and update the cart's content
	 */
	public function updateOrders(): self {
		$this->updateQuantity();
		$this->updatePrice();

		return $this;
	}

	/**
	 * Recheck and update the cart's product quantity
	 */
	public function updateQuantity(): self {
		$this->quantity = $this->orders->sum('quantity');
		$this->save();

		if (!$this->quantity) {
			$this->delete();
		}

		return $this;
	}

	/**
	 * Recheck and update the cart's price
	 */
	public function updatePrice(): self {
		$orderPrice = $this->orderPrice();
		$shippingPrice = $this->shippingPrice();

		$this->price = $orderPrice + $shippingPrice;
		// $this->save();

		return $this;
	}

	/**
	 * Confirm the cart
	 */
	public function confirm(bool $sendNotification = true): void {
		// Update the cart
		$this->update([
			'created_at' => date('Y-m-d H:i:s'),
			'status' => 1,
		]);

		// Remove the shipping price from the user's credit, if it was payed with the credit
		if ($this->credit_ship && $this->user && $this->user->isSeller()) {
			$this->user->seller->update(['credit' => $this->user->seller->credit - $this->grossShippingPrice()]);
		}

		// Destroy used coupons
		if ($this->coupon) {
			if ($this->coupon->one_per_acccount) {
				UsedCoupon::create(['coupon_id' => $this->coupon->id, 'user_id' => $this->user_id]);
			}

			if (!$this->coupon->permanent) {
				$this->coupon->delete();
			}
		}

		// Send the confirmation email
		Mail::to($this->address->email)->send(new OrderStatusMail($this));

		// Confirm the individual orders
		$this->confirmOrders();
	}

	/**
	 * Confirm the individual orders in the cart
	 */
	public function confirmOrders(): void {
		foreach ($this->orders as $order) {
			// Check if the order is in the inventory
			$inventoryItem = InventoryItem::where('design_id', $order->design_id)
				->whereIn('type_id', [$order->type_id, $order->type->same_as_type_id ?? 0])
				->where('color_id', $order->color_id)
				->where('size_id', $order->size_id)
				->where('position_id', $order->position_id)
				->first();

			if ($inventoryItem) {
				$order->update(['inventory' => 1]);
				$inventoryItem->delete();
			}

			// Update the seller's data
			if ($order->seller) {
				// Update the amount earned by the seller
				$order->seller->update(['earned' => $order->seller->earned + $order->totalGain()]);

				// Update the seller's credit
				if ($order->credit) {
					// Remove from the seller's credit if they used it to pay for the order
					$order->seller->update(['credit' => $order->seller->credit - $order->price()]);
				} else if ($order->seller_id !== $this->user_id) {
					// Add to the seller's credit if no credit was used to pay for the order and the buyer isn't the seller
					$order->seller->update(['credit' => $order->seller->credit + ($order->totalGain() * .1)]);
				}
			}
		}
	}

	/**
	 * Delete the cart
	 */
	public function delete(): bool {
		Session::forget('cart_id');

		return parent::delete();
	}

	/************************
	 * * Static methods *
	 ************************/
	/**
	 * Get the unlabelled carts
	 */
	public static function unlabelled(): Collection {
		return self::where('status', 1)
			->whereIn('shipping_method_id', ShippingMethod::shippingMethodsWithLabels())
			->get();
	}
}
