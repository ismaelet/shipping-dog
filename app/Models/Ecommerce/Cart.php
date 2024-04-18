<?php

namespace App\Models\Ecommerce;

use App\Helpers\EcommerceHelper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Session;

use function Ecommerce\ecommerce_config;

class Cart extends Model {

	protected $table = 'carts';
	protected $fillable = ['user_id', 'shipping_method_id', 'country_id', 'payment_method_id', 'code', 'quantity', 'credit_ship', 'shipping', 'status', 'step', 'time', 'courier_notes', 'shipping_notes', 'has_errors'];

	/***************************
	 * * Relationship instances *
	 ***************************/
	/**
	 * Get the cart's shipping method
	 */
	public function shipping_method(): BelongsTo {
		return $this->belongsTo(ShippingMethod::class);
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
		return '#' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
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

	/**
	 * Check if the cart has long delivery times
	 */
	public function longDeliveryTime(): bool {
		foreach ($this->orders as $order) {
			if ($order->product->type->preorder || $order->color->preorder) {
				return true;
			}
		}

		return false;
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
	public function updateOrders(): self {
		$this->updateQuantity();
		$this->updatePrice();

		return $this;
	}

	public function updateQuantity(): self {
		$this->quantity = $this->orders->sum('quantity');
		$this->save();

		if (!$this->quantity) {
			$this->delete();
		}

		return $this;
	}

	public function updatePrice(): self {
		$orderPrice = $this->orderPrice();
		$shippingPrice = $this->shippingPrice();

		$this->price = $orderPrice + $shippingPrice;
		// $this->save();

		return $this;
	}

	public function delete(): bool {
		Session::forget('cart_id');

		return parent::delete();
	}

	// * Collections * //
	/**
	 * Get the unlabelled carts
	 */
	public static function unlabelled(): Collection {
		return self::where('status', 1)
			->whereIn('shipping_method_id', ShippingMethod::shippingMethodsWithLabels())
			->get();
	}

	// ! Deprecated ! //
	public function statusCode(): string {
		return ecommerce_config('cart-statuses')[$this->status][0];
	}

	public function statusName() {
		return ecommerce_config('cart-statuses')[$this->status][1];
	}
}
