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
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/**
 * Represents a shopping cart in the e-commerce system.
 */
class Cart extends Model {

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['user_id', 'shipping_method_id', 'country_id', 'payment_method_id', 'code', 'quantity', 'credit_ship', 'shipping', 'status', 'step', 'time', 'courier_notes', 'shipping_notes', 'has_errors', 'created_at', 'updated_at'];


	// * Relationships * //
	/**
	 * Get the cart's user.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user(): BelongsTo {
		return $this->belongsTo(User::class);
	}

	/**
	 * Get the cart's shipping method.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function shipping_method(): BelongsTo {
		return $this->belongsTo(ShippingMethod::class);
	}

	/**
	 * Get the cart's payment method.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function payment_method(): BelongsTo {
		return $this->belongsTo(PaymentMethod::class);
	}

	/**
	 * Get the cart's address.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function address(): HasOne {
		return $this->hasOne(Address::class);
	}

	/**
	 * Get the cart's coupon.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function coupon(): HasOne {
		return $this->hasOne(Coupon::class, 'code', 'code');
	}

	/**
	 * Get the cart's change request.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function change_request(): HasOne {
		return $this->hasOne(ChangeRequest::class, 'new_cart_id');
	}

	/**
	 * Get the cart's orders.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function orders(): HasMany {
		return $this->hasMany(Order::class);
	}


	// * Attributes * //
	/**
	 * Get the cart's code.
	 *
	 * @return string
	 */
	public function idCode(): string {
		return '#' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
	}

	/**
	 * Get the cart's created at date as a string.
	 *
	 * @return string
	 */
	public function createdAtString(): string {
		return date('d/m/Y', strtotime($this->created_at));
	}

	/**
	 * Get the cart's recap URL.
	 *
	 * @return string
	 */
	public function recapUrl(): string {
		return '/order?id=' . $this->id . '&customerId=' . $this->user_id;
	}

	/**
	 * Check if the cart has a long delivery time.
	 *
	 * @return bool
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
	 * Get the cart's orders as a string.
	 *
	 * @return string
	 */
	public function ordersString(): string {
		$ordersString = '';

		foreach ($this->orders as $order) {
			$ordersString .= $order->quantity . 'x ' . $order->fullName() . '<br>';
		}

		return $ordersString;
	}


	// ~ Attributes: Steps ~ //
	/**
	 * Get the cart's step tag.
	 *
	 * @return string
	 */
	public function stepTag(): string {
		return config('ecommerce.cart.steps')[$this->step]['tag'];
	}


	// ~ Attributes: Status ~ //
	/**
	 * Get the cart's status tag.
	 *
	 * @return string
	 */
	public function statusTag(): string {
		return config('ecommerce.cart.statuses')[$this->status]['tag'];
	}

	/**
	 * Get the cart's status name.
	 *
	 * @return string
	 */
	public function statusName(): string {
		return config('ecommerce.cart.statuses')[$this->status]['label'];
	}

	/**
	 * Get the cart's status color.
	 *
	 * @return string
	 */
	public function statusColor(): string {
		return config('ecommerce.cart.statuses')[$this->status]['color'];
	}

	/**
	 * Get the status icon.
	 *
	 * @return string
	 */
	public function statusIcon(): string {
		return config('ecommerce.cart.statuses')[$this->status]['icon'];
	}

	/**
	 * Get the cart's status label.
	 *
	 * @return string
	 */
	public function statusLabel(): string {
		return '<span class="px-4 py-1 gap-2 text-background rounded whitespace-nowrap" style="--icon-size: 1em; background-color: ' . $this->statusColor() . ';">' . embed_icon($this->statusIcon()) . ' ' . $this->statusName() . '</span>';
	}


	// ~ Attributes: Price ~ //
	/**
	 * Get the cart's total price.
	 *
	 * @return float
	 */
	public function price(): float {
		return $this->updatePrice()->price;
	}

	/**
	 * Get the cart's orders net price.
	 *
	 * @return float
	 */
	public function ordersPrice(): float {
		$ordersPrice = 0;

		foreach ($this->orders as $order) {
			$ordersPrice += $order->price();
		}

		return $ordersPrice;
	}

	/**
	 * Get the cart's orders gross price.
	 *
	 * @return float
	 */
	public function grossOrdersPrice(): float {
		$grossOrdersPrice = 0;

		foreach ($this->orders as $order) {
			$grossOrdersPrice += $order->grossPrice();
		}

		return $grossOrdersPrice;
	}

	/**
	 * Get the cart's net shipping fee.
	 *
	 * @return float
	 */
	public function shippingFee(): float {
		return $this->credit_ship ? 0 : $this->grossShippingFee();
	}

	/**
	 * Get the cart's gross shipping fee.
	 *
	 * @return float
	 */
	public function grossShippingFee(): float {
		return $this->shipping_method ? $this->shipping_method->price($this->orders->sum('quantity')) : 0;
	}

	/**
	 * Get the cart's total discount.
	 *
	 * @return float
	 */
	public function discount(): float {
		return $this->ordersDiscount() + $this->shippingDiscount();
	}

	/**
	 * Get the cart's total orders discount.
	 *
	 * @return float
	 */
	public function ordersDiscount(): float {
		$ordersDiscount = 0;

		foreach ($this->orders as $order) {
			$ordersDiscount += $order->discount();
		}

		return $ordersDiscount;
	}

	/**
	 * Get the cart's shipping discount.
	 *
	 * @return float
	 */
	public function shippingDiscount(): float {
		return $this->credit_ship ? $this->grossShippingFee() : 0;
	}

	/**
	 * Get the cart's price formatted as a price string.
	 *
	 * @return string
	 */
	public function priceString(): string {
		return EcommerceHelper::formatPrice($this->price());
	}

	/**
	 * Get the cart's gross orders price formatted as a price string.
	 *
	 * @return string
	 */
	public function grossOrdersPriceString(): string {
		return EcommerceHelper::formatPrice($this->grossOrdersPrice());
	}

	/**
	 * Get the cart's shipping fee formatted as a price string.
	 *
	 * @return string
	 */
	public function shippingFeeString(): string {
		return EcommerceHelper::formatPrice($this->shippingFee());
	}

	/**
	 * Get the cart's total discount formatted as a price string.
	 *
	 * @return string
	 */
	public function discountString(): string {
		return EcommerceHelper::formatPrice($this->discount());
	}

	/**
	 * Get the cart's orders discount formatted as a price string.
	 *
	 * @return string
	 */
	public function ordersDiscountString(): string {
		return EcommerceHelper::formatPrice($this->ordersDiscount());
	}


	// * Actions * //
	/**
	 * Advance the cart to the next step or to a specific step.
	 *
	 * @param string|null $advanceToStepTag The tag of the step to advance to (optional)
	 * @return self The updated Cart instance
	 */
	public function advance(?string $advanceToStepTag = null): self {
		// If no specific step is given, advance to the next step
		if (!$advanceToStepTag) {
			// The current cart step
			$currentStep = $this->step;

			// The page step
			$pageStepTag = explode('.', Route::currentRouteName())[1] ?? null;
			$pageStep = EcommerceHelper::stepFromTag($pageStepTag);

			// Advance to the next step, if the current cart step is equal to the page step
			if ($currentStep == $pageStep) {
				$this->step++;
				$this->save();
			}
		} else {
			// Advance to the given step
			$advanceToStep = EcommerceHelper::stepFromTag($advanceToStepTag);

			if ($advanceToStep) {
				$this->step = $advanceToStep;
				$this->save();
			}
		}

		return $this;
	}

	/**
	 * Update the cart's quantity and price.
	 *
	 * @return self
	 */
	public function updateOrders(): self {
		$this->updateQuantity();
		$this->updatePrice();

		return $this;
	}

	/**
	 * Update the cart's quantity.
	 *
	 * @return self
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
	 * Update the cart's price.
	 *
	 * @return self
	 */
	public function updatePrice(): self {
		$ordersPrice = $this->ordersPrice();
		$shippingFee = $this->shippingFee();

		$this->price = $ordersPrice + $shippingFee;
		$this->save();

		return $this;
	}

	/**
	 * Confirm the cart.
	 *
	 * @param bool $sendNotification
	 * @return void
	 */
	public function confirm(bool $sendNotification = true): void {
		// Update the cart
		$this->update([
			'created_at' => date('Y-m-d H:i:s'),
			'status' => 1,
		]);

		// Remove the shipping price from the user's credit, if it was paid with the credit
		if ($this->credit_ship && $this->user && $this->user->isSeller()) {
			$this->user->seller->update(['credit' => $this->user->seller->credit - $this->grossShippingFee()]);
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
		if ($sendNotification) {
			Mail::to($this->address->email)->send(new OrderStatusMail($this));
		}

		// Confirm the individual orders
		$this->confirmOrders();
	}

	/**
	 * Confirm the cart's orders.
	 *
	 * @return void
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
	 * Delete the cart.
	 *
	 * @return bool
	 */
	public function delete(): bool {
		Session::forget('cart_id');

		return parent::delete();
	}


	// * Static methods * //
	/**
	 * Get the carts that need a label to be printed.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public static function unlabelled(): Collection {
		return self::where('status', 1)
			->whereIn('shipping_method_id', ShippingMethod::shippingMethodsWithLabels())
			->get();
	}
}
