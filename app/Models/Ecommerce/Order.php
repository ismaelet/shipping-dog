<?php

namespace App\Models\Ecommerce;

use App\Helpers\EcommerceHelper;
use App\Mail\OrderStatusMail;
use App\Models\User;
use App\Services\GlsService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/**
 * Represents a shopping order in the e-commerce system.
 */
class Order extends Model {

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'user_id',
		'shipping_method_id',
		'payment_method_id',
		'code',
		'quantity',
		'credit_ship',
		'shipping',
		'status',
		'step',
		'courier_notes',
		'shipping_notes',
		'has_errors',
		'created_at',
		'updated_at',
		'token',
		'tracking'
	];

	/**
	 * * Events
	 */

	/**
	 * Update the order's order_items status when the order is updated.
	 * 
	 * @param array $attributes
	 * @param array $options
	 * @return bool
	 */
	public function update(array $attributes = [], array $options = []): bool {
		// Synchronize the order_items status with the order status
		if (isset($attributes['status'])) {
			$this->order_items->each->update([
				'status' => $attributes['status'],
			]);
		}

		// Synchronize the order_items created_at with the order created_at
		if (isset($attributes['created_at'])) {
			$this->order_items->each->update([
				'created_at' => $attributes['created_at'],
			]);
		}

		return parent::update($attributes, $options);
	}

	/**
	 * * Relationships
	 */

	/**
	 * Get the order's user.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user(): BelongsTo {
		return $this->belongsTo(User::class);
	}

	/**
	 * Get the order's shipping method.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function shipping_method(): BelongsTo {
		return $this->belongsTo(ShippingMethod::class);
	}

	/**
	 * Get the order's payment method.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function payment_method(): BelongsTo {
		return $this->belongsTo(PaymentMethod::class);
	}

	/**
	 * Get the order's address.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function address(): HasOne {
		return $this->hasOne(Address::class);
	}

	/**
	 * Get the order's coupon.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function coupon(): HasOne {
		return $this->hasOne(Coupon::class, 'code', 'code');
	}

	/**
	 * Get the order's change request.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function change_request(): HasOne {
		return $this->hasOne(ChangeRequest::class, 'new_order_id');
	}

	/**
	 * Get the order's order_items.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function order_items(): HasMany {
		return $this->hasMany(OrderItem::class);
	}


	/**
	 * * Get methods
	 */

	/**
	 * Get the order's code.
	 *
	 * @return string
	 */
	public function idCode(): string {
		return '#' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
	}

	/**
	 * Get the order's created at date as a string.
	 *
	 * @return string
	 */
	public function createdAtString(): string {
		return date('d/m/Y', strtotime($this->created_at));
	}

	/**
	 * Get the order's payment URL.
	 *
	 * @return string
	 */
	public function paymentUrl(): string {
		return route('paypal.orders.create', ['order_id' => $this->id]);
	}

	/**
	 * Get the order's recap URL.
	 *
	 * @return string
	 */
	public function recapUrl(): string {
		return route('orders.show', ['order' => $this->id]);
	}

	/**
	 * Get the order's recap URL with the security token.
	 *
	 * @return string
	 */
	public function recapUrlWithToken(): string {
		return route('orders.show', ['order' => $this->id, 'token' => $this->token]);
	}

	/**
	 * Check if the order has a long delivery time.
	 *
	 * @return bool
	 */
	public function hasLongDelivery(): bool {
		foreach ($this->order_items as $orderItem) {
			if ($orderItem->product && ($orderItem->product->type?->preorder || $orderItem->color?->preorder)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get the order's order_items as a string.
	 *
	 * @return string
	 */
	public function orderItemsString(): string {
		$orderItemsString = '';

		if (!$this->order_items->count()) {
			return 'Nessun prodotto';
		}

		foreach ($this->order_items as $orderItem) {
			$orderItemsString .= $orderItem->full_name . '<br>';
		}

		return $orderItemsString;
	}


	/**
	 * ~ Attributes: Steps
	 */
	/**
	 * Get the order's step data.
	 *
	 * @return array
	 */
	public function stepData(): array {
		return config('ecommerce.cart.steps')[$this->step];
	}

	/**
	 * Get the order's step tag.
	 *
	 * @return string
	 */
	public function stepTag(): string {
		return $this->stepData()['tag'];
	}


	/**
	 * ~ Attributes: Status
	 */
	/**
	 * Get the order's status data.
	 *
	 * @return string
	 */
	public function statusData(): array {
		return config('ecommerce.order.statuses')[$this->status];
	}

	/**
	 * Get the order's status tag.
	 *
	 * @return string
	 */
	public function statusTag(): string {
		return $this->statusData()['tag'];
	}

	/**
	 * Get the order's status name.
	 *
	 * @return string
	 */
	public function statusName(): string {
		return $this->statusData()['label'];
	}

	/**
	 * Get the order's status color.
	 *
	 * @return string
	 */
	public function statusColor(): string {
		return $this->statusData()['color'];
	}

	/**
	 * Get the status icon.
	 *
	 * @return string
	 */
	public function statusIcon(): string {
		return $this->statusData()['icon'];
	}

	/**
	 * ~ Attribute: Status Label
	 * 
	 * A stylized html span element to show the order's status
	 */
	public function statusLabel(): string {
		return '<span class="order-label px-4 py-1 gap-2 border rounded whitespace-nowrap" style="--icon-size: 1em; border-color: ' . $this->statusColor() . '; color: ' . $this->statusColor() . ';">' . embed_icon($this->statusIcon(), ['shrink-0']) . ' ' . $this->statusName() . '</span>';
	}

	/**
	 * ~ Attribute: Order Items Price
	 * 
	 * The sum of the order item's prices
	 */
	public function orderItemsPrice(): float {
		$orderItemsPrice = 0;

		foreach ($this->order_items as $orderItem) {
			$orderItemsPrice += $orderItem->price();
		}

		return $orderItemsPrice;
	}

	/**
	 * ~ Attribute: Full Order Items Price
	 * 
	 * The sum of the order item's prices, not including any discount
	 */
	public function fullOrderItemsPrice(): float {
		$fullOrderItemsPrice = 0;

		foreach ($this->order_items as $orderItem) {
			$fullOrderItemsPrice += $orderItem->grossPrice();
		}

		return $fullOrderItemsPrice;
	}

	/**
	 * Get the order's net shipping fee.
	 *
	 * @return float
	 */
	public function shippingFee(): float {
		return $this->credit_ship ? 0 : $this->grossShippingFee();
	}

	/**
	 * Get the order's gross shipping fee.
	 *
	 * @return float
	 */
	public function grossShippingFee(): float {
		return $this->shipping_method ? $this->shipping_method->price($this->order_items->sum('quantity')) : 0;
	}

	/**
	 * Get the order's total discount.
	 *
	 * @return float
	 */
	public function discount(): float {
		return $this->orderItemsDiscount() + $this->shippingDiscount();
	}

	/**
	 * Get the order's total order_items discount.
	 *
	 * @return float
	 */
	public function orderItemsDiscount(): float {
		$orderItemsDiscount = 0;

		foreach ($this->order_items as $orderItem) {
			$orderItemsDiscount += $orderItem->discount();
		}

		return $orderItemsDiscount;
	}

	/**
	 * ~ Attribute: Shipping Discount
	 *
	 * The discount on the shipping fee
	 */
	public function shippingDiscount(): float {
		return $this->credit_ship ? $this->grossShippingFee() : 0;
	}

	/**
	 * ~ Attribute: Price String
	 *
	 * The order's price formatted as a price string
	 */
	public function priceString(): string {
		return EcommerceHelper::formatPrice($this->price);
	}

	/**
	 * ~ Attribute: Full Order Items Price String
	 *
	 * The sum of the order items price, without any discount, formatted as a price string
	 */
	public function fullOrderItemsPriceString(): string {
		return EcommerceHelper::formatPrice($this->fullOrderItemsPrice());
	}

	/**
	 * ~ Attribute: Shipping Fee String
	 *
	 * The shipping fee formatted as a price string
	 */
	public function shippingFeeString(): string {
		return EcommerceHelper::formatPrice($this->shippingFee());
	}

	/**
	 * Get the order's total discount formatted as a price string.
	 *
	 * @return string
	 */
	public function discountString(): string {
		return EcommerceHelper::formatPrice($this->discount());
	}

	/**
	 * Get the order's order_items discount formatted as a price string.
	 *
	 * @return string
	 */
	public function orderItemsDiscountString(): string {
		return EcommerceHelper::formatPrice($this->orderItemsDiscount());
	}


	/**
	 * * Actions
	 */

	/**
	 * Advance the order to the next step or to a specific step.
	 *
	 * @param string|null $advanceToStepTag The tag of the step to advance to (optional)
	 * @return self The updated order instance
	 */
	public function advance(?string $advanceToStepTag = null): self {
		// If no specific step is given, advance to the next step
		if (!$advanceToStepTag) {
			// The current order step
			$currentStep = $this->step;

			// The page step
			$pageStepTag = explode('.', Route::currentRouteName())[1] ?? null;
			$pageStep = EcommerceHelper::stepFromTag($pageStepTag);

			// Advance to the next step, if the current order step is equal to the page step
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
	 * Update the order's quantity and price.
	 *
	 * @return self
	 */
	public function updateOrderItems(): self {
		$this->updateQuantity();
		$this->updatePrice();

		return $this;
	}

	/**
	 * Update the order's quantity.
	 *
	 * @return self
	 */
	public function updateQuantity(): self {
		$this->quantity = $this->order_items->sum('quantity');
		$this->save();

		if (!$this->quantity) {
			$this->delete();
		}

		return $this;
	}

	/**
	 * Update the order's price.
	 *
	 * @return self
	 */
	public function updatePrice(): self {
		$orderItemsPrice = $this->orderItemsPrice();
		$shippingFee = $this->shippingFee();

		$this->price = $orderItemsPrice + $shippingFee;
		$this->shipping = $shippingFee;
		$this->save();

		return $this;
	}

	/**
	 * Confirm the order.
	 *
	 * @param bool $sendNotification
	 * @return void
	 */
	public function confirm(bool $sendNotification = true, bool $updateDate = true): void {
		// Forget the order ID
		session()->forget('cart_id');

		// Update the order
		if ($updateDate) {
			$this->update([
				'created_at' => now(),
			]);
		}

		$this->update([
			'status' => EcommerceHelper::statusFromTag('confirmed'),
		]);

		// Remove the shipping price from the user's credit, if it was paid with the credit
		if ($this->credit_ship) {
			$this->user->seller?->updateCredit();
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

		// Confirm the individual order_items
		$this->confirmOrderItems();

		// Send the confirmation email
		if ($sendNotification) {
			$this->sendStatusEmail();
		}

		// Send the shipping data
		$glsService = new GlsService();
		$glsService->addParcels($this);
	}

	/**
	 * Confirm the order's order_items.
	 *
	 * @return void
	 */
	public function confirmOrderItems(): void {
		foreach ($this->order_items as $orderItem) {
			// Check if the order is in the inventory
			$inventoryItem = InventoryItem::where('design_id', $orderItem->design_id)
				->whereIn('type_id', [$orderItem->type_id, $orderItem->type->same_as_type_id ?? 0])
				->where('color_id', $orderItem->color_id)
				->where('size_id', $orderItem->size_id)
				->where('position_id', $orderItem->position_id)
				->first();

			if ($inventoryItem) {
				$orderItem->update(['inventory' => 1]);
				$inventoryItem->delete();
			}

			// Update the seller's data
			if ($orderItem->seller) {
				// Update the amount earned by the seller
				$orderItem->seller->updateEarned();

				// Update the seller's credit
				if ($orderItem->credit) {
					$orderItem->seller->updateCredit();
				}
			}
		}
	}

	/**
	 * Cancel an order
	 */
	public function cancel(bool $sendNotification = false): void {

		// Update the order
		$this->update([
			'status' => EcommerceHelper::statusFromTag('cancelled'),
		]);

		// Send the status email
		if ($sendNotification) {
			$this->sendStatusEmail();
		}

		// Update the seller's earnings
		$this->order_items->each(function ($orderItem) {
			if ($orderItem->seller) {
				$orderItem->seller->updateEarned();
				$orderItem->seller->updateCredit();
			}
		});
	}

	/**
	 * Send the order's status email.
	 */
	public function sendStatusEmail(): void {
		Mail::to($this->address->email)->queue(new OrderStatusMail($this));
	}

	/**
	 * Delete the order.
	 *
	 * @return bool
	 */
	public function delete(): bool {
		session()->forget('cart_id');

		return parent::delete();
	}

	/**
	 * * Query builders
	 */

	public static function hasBeenPaid(): Builder {
		return self::where('status', '>=', EcommerceHelper::statusFromTag('confirmed'));
	}

	public static function hasNoLabel(): Builder {
		return self::join('addresses', 'orders.id', '=', 'addresses.order_id')
			->where('status', EcommerceHelper::statusFromTag('confirmed'))
			->whereIn('shipping_method_id', ShippingMethod::shippingMethodsWithLabels())
			->oldest('orders.created_at')
			->select('orders.*', 'addresses.full_name');
	}


	/**
	 * * Static methods
	 */

	/**
	 * Get the orders that need a label to be printed.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public static function unlabelled(): Collection {
		return self::join('addresses', 'orders.id', '=', 'addresses.order_id')
			->where('status', EcommerceHelper::statusFromTag('confirmed'))
			->whereIn('shipping_method_id', ShippingMethod::shippingMethodsWithLabels())
			->oldest()
			->select('orders.*', 'addresses.full_name')
			->get();
	}
}
