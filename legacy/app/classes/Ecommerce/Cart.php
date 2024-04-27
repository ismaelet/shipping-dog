<?php

namespace Ecommerce;

use App\Models\Ecommerce\Cart as EcommerceCart;
use App\Models\Ecommerce\Order as EcommerceOrder;
use DBOld;
use EmailService\Ecommerce\CartStatusUpdate;
use DatabaseObject;
use ViewOld;

/**
 * Cart Object
 *
 * @property int customerId
 * 
 * @property object shippingMethod
 * 
 * @property int couponId
 * @property object coupon
 * 
 * @property float gain
 * @property float usedCredit
 */
class Cart {

	use DatabaseObject;

	public const TABLE = 'carts';
	public const INDEX = 'id';
	public const CUSTOMER_INDEX = 'user_id';
	public const SHIPPING_METHOD_INDEX = 'shipping_method_id';
	public const PAYMENT_METHOD_INDEX = 'payment_method_id';
	public const COUNTRY_INDEX = 'country_id';
	public const STEP_INDEX = 'step';
	public const STATUS_INDEX = 'status';

	private function getId() {
		return $this->databaseEntry[self::INDEX];
	}

	private function getCustomerId() {
		return $this->databaseEntry[self::CUSTOMER_INDEX];
	}

	private function getCustomer() {
		return $this->customerId ? new Customer($this->customerId) : new Customer();
	}

	private function getCountryId() {
		return $this->databaseEntry[self::COUNTRY_INDEX];
	}

	private function getShippingMethodId() {
		return $this->databaseEntry[self::SHIPPING_METHOD_INDEX] ?: 2;
	}

	private function getShippingWithCredit() {
		return $this->credit_ship;
	}

	private function getpaymentMethodId() {
		return $this->databaseEntry[self::PAYMENT_METHOD_INDEX] ?: 1;
	}

	private function getCouponCode() {
		return $this->code ?: null;
	}

	private function getStepId() {
		return $this->databaseEntry[self::STEP_INDEX];
	}

	private function getStatusId() {
		$statusId = $this->databaseEntry[self::STATUS_INDEX];

		return $statusId;
	}

	private function getIdCode() {
		return '#' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
	}

	private function getAddressId() {
		$addressId = DBOld::find(Address::INDEX . ' FROM ' . Address::TABLE . ' WHERE ' . Address::CART_INDEX . ' = ? ORDER BY ' . Address::INDEX . ' DESC', [$this->id]);

		return $addressId;
	}

	private function getAddress() {
		return new Address($this->addressId);
	}

	private function getCouponId() {
		return DBOld::find('id FROM codes WHERE code=?', [$this->couponCode]);
	}

	private function getLongDeliveryTime() {
		$longDeliveryTime = false;

		foreach ($this->orders as $order) {
			if ($order->type->preorder || $order->color->preorder) {
				$longDeliveryTime = true;
				break;
			}
		}

		return $longDeliveryTime;
	}

	private function getDateString() {
		return date('d/m/Y', strtotime($this->time));
	}

	private function getUrl() {
		return '/order?id=' . $this->id . '&customerId=' . $this->customerId;
	}

	private function getClientUrl() {
		return public_client_url($this->url);
	}

	private function getStaticUrl() {
		return CLIENT_URL . $this->url;
	}

	private function getOpenUrl() {
		return '/cart?step-id=' . $this->stepId;
	}

	private function getClientOpenUrl() {
		return client_path($this->openUrl);
	}

	private function getStatusCode() {
		return ecommerce_config('cart-statuses')[$this->statusId][0];
	}

	private function getStatusName() {
		return ecommerce_config('cart-statuses')[$this->statusId][1];
	}

	private function getAdminStatusName() {
		return ecommerce_config('cart-admin-statuses')[$this->statusId][1];
	}

	private function getStatusColor() {
		return ecommerce_config('cart-statuses')[$this->statusId][2];
	}

	private function getStatusIcon() {
		return ecommerce_config('cart-statuses')[$this->statusId][3];
	}

	private function getprice() {
		return $this->calcPrice();
	}

	private function getPriceString() {
		return format_price($this->price);
	}

	private function getProductPrice() {
		return $this->calcproductPrice();
	}

	private function getProductPriceString() {
		return format_price($this->productPrice);
	}

	private function getfullProductPrice() {
		$productPrice = 0;

		foreach ($this->orders as $order) {
			$productPrice += $order->fullPrice;
		}

		return $productPrice;
	}

	private function getfullProductPriceString() {
		return format_price($this->fullProductPrice);
	}

	private function getDiscount() {
		return $this->calcDiscount();
	}

	private function getDiscountFrm() {
		return format($this->discount);
	}

	private function getDiscountString() {
		return format_price($this->discount);
	}

	private function getshippingPrice() {
		return !$this->shippingWithCredit ? $this->fullShippingPrice : 0;
	}

	private function getShippingPriceFrm() {
		return format($this->shippingPrice);
	}

	private function getShippingPriceString() {
		return format_price($this->shippingPrice);
	}

	private function getCredit() {
		return $this->customer->credit;
	}

	private function getUsableCredit() {
		return $this->customer->credit - $this->usedCredit;
	}

	private function getGain() {
		$gain = 0;

		foreach ($this->orders as $order) {
			$gain += $order->gain;
		}

		return round($gain, 2);
	}

	private function getPromotion() {
		return false;
	}

	private function getShippingMethod() {
		return (new ShippingMethod($this->shippingMethodId))
			->setAssociatedCart($this);
	}

	private function getPaymentMethod() {
		return new PaymentMethod($this->paymentMethodId);
	}

	private function getCoupon() {
		return new Coupon($this->couponId);
	}

	private function getQuantity() {
		return DBOld::count(Order::TABLE . ' WHERE ' . Order::CART_INDEX . ' = ?', [$this->id]);
	}

	private function getLocalDelivery() {
		return $this->shippingMethod->localDelivery;
	}

	/*
	* Misc methods
	*/
	private function getStatusIdFromCode(string $statusCode) {
		$statusId = null;

		foreach (ecommerce_config('cart-statuses') as $key => $status) {
			if ($status[0] == $statusCode) {
				$statusId = $key;
			}
		}

		return $statusId;
	}

	/*
	* Prices
	*/
	private function calcPrice() {
		return $this->data['totalPrice'] = round($this->fullProductPrice - $this->discount + $this->shippingPrice, 2);
	}

	private function calcproductPrice() {
		$productPrice = 0;

		foreach ($this->orders as $order) {
			$productPrice += $order->price;
		}

		return $productPrice;
	}

	private function calcDiscount() {
		$discount = 0;

		foreach ($this->orders as $order) {
			$discount += $order->discount;
		}

		return $discount;
	}

	private function getFullShippingPrice() {
		return $this->shippingMethod->price;
	}

	private function getNotFreeShippingPrice() {
		return (new ShippingMethod(ShippingMethod::PRIMARY_SHIPPING_METHOD_ID))
			->setAssociatedCart($this)
			->price;
	}

	private function getUsedProductCredit() {
		$usedCredit = 0;

		foreach ($this->orders as $order) {
			if ($order->payedWithCredit) {
				$usedCredit += $order->product->price;
			}
		}

		return round($usedCredit, 2);
	}

	private function getUsedShippingCredit() {
		return $this->shippingWithCredit ? $this->notFreeShippingPrice : 0;
	}

	private function getUsedCredit() {
		return $this->usedProductCredit + $this->usedShippingCredit;
	}

	private function getUsedCreditString() {
		return format_price($this->usedCredit);
	}

	/*
	* Orders
	*/
	private function getOrders() {
		$orders = [];
		$ordersRes = DBOld::select('id FROM orders WHERE cart_id = ? ORDER BY time ASC', [$this->id]);

		while ($order = $ordersRes->fetch_assoc()) {
			$orders[] = new Order($order['id']);
		}

		return $orders;
	}


	// calculate the delivery date
	public function getDeliveryDate() {
		return $this->shippingMethod->deliveryDate;
	}

	// update the database entry for this cart after changes
	public function update($recalculate = true) {
		if ($recalculate) {
			unset($this->quantity);
		}

		if ($this->quantity)
			DBOld::update(
				'
				carts SET tot = ?, credit = ?, shipping_method_id = ?, shipping = ?, credit_ship = ? WHERE id = ?',
				[$this->price, $this->usedCredit, $this->shipping_method_id, $this->fullShippingPrice, $this->shippingWithCredit, $this->id]
			);
		else {
			DBOld::delete('carts WHERE id = ?', $this->id);
		}
	}

	public function updateTracking($tracking) {
		$this->data['tracking'] = trim($tracking);

		DBOld::update('carts SET tracking=? WHERE id = ?', [$this->tracking, $this->id]);
		//if ($this->tracking) send_tracking_email($this->id);
	}

	/**
	 * Set methods
	 */
	public function setShippingMethodId(int $shippingMethodId) {
		$shippingMethod = new ShippingMethod($shippingMethodId);

		if ($shippingMethod->id) {
			$this->data['shippingMethodId'] = $shippingMethodId;
			$this->data['shippingMethod'] = [];
		}

		return $this;
	}

	public function setPaymentMethodId(int $paymentMethodId) {
		$paymentMethod = new PaymentMethod($paymentMethodId);

		if ($paymentMethod->id) {
			$this->data['paymentMethodId'] = $paymentMethodId;
		}

		return $this;
	}

	public function setShippingWithCredit(bool $usingCredit) {
		$this->data['shippingWithCredit'] = $usingCredit;

		return $this;
	}

	public function setStepId(int $stepId) {
		$this->data['stepId'] = $stepId;

		return $this;
	}

	public function setStatusId(int $statusId) {
		$this->data['statusId'] = $statusId;

		unset($this->data['statusCode']);
		unset($this->data['statusName']);
		unset($this->data['adminStatusName']);
		unset($this->data['statusColor']);
		unset($this->data['statusIcon']);

		return $this;
	}

	/**
	 * Update methods
	 */
	public function updateShippingMethodId(int $shippingMethodId) {
		$shippingMethod = new ShippingMethod($shippingMethodId);

		if ($shippingMethod->id) {
			DBOld::update(self::TABLE . ' SET ' . self::SHIPPING_METHOD_INDEX . ' = ? WHERE ' . self::INDEX . ' = ?', [$shippingMethod->id, $this->id]);

			$this->setShippingMethodId($shippingMethod->id);
		}

		return $this;
	}

	public function updateShippingWithCredit(bool $usingCredit) {
		DBOld::update(self::TABLE . ' SET credit_ship = ? WHERE ' . self::INDEX . ' = ?', [$usingCredit ? 1 : 0, $this->id]);

		$this->setShippingWithCredit($usingCredit);

		return $this;
	}

	public function updatePaymentMethodId(int $paymentMethodId) {
		$paymentMethod = new PaymentMethod($paymentMethodId);

		if ($paymentMethod->id) {
			DBOld::update(self::TABLE . ' SET ' . self::PAYMENT_METHOD_INDEX . ' = ? WHERE ' . self::INDEX . ' = ?', [$paymentMethod->id, $this->id]);

			$this->setPaymentMethodId($paymentMethod->id);
		}

		return $this;
	}

	public function updateStepId(int $stepId) {
		EcommerceCart::find($this->id)
			->update(['step' => $stepId]);

		$this->setStepId($stepId);

		return $this;
	}

	public function updateStatusId(int $statusId, $sendNotification = true) {
		DBOld::update(self::TABLE . ' SET ' . self::STATUS_INDEX . ' = ?, ' . self::STEP_INDEX . ' = ? WHERE ' . self::INDEX . ' = ?', [$statusId, count(ecommerce_config('cart-page')['steps']), $this->id]);

		$this->setStatusId($statusId);

		if ($statusId == 1 && $sendNotification) {
			$this->sendStatusEmail();
		}

		return $this;
	}

	public function updateStatus(string $statusCode, bool $sendNotification = true) {
		$statusId = $this->getStatusIdFromCode($statusCode);

		$this->updateStatusId($statusId, $sendNotification);

		return $this;
	}

	/*
	* Istance methods
	*/
	public function delete() {
		DBOld::delete(self::TABLE . ' WHERE ' . self::INDEX . ' = ?', [$this->id]);
		DBOld::delete(Order::TABLE . ' WHERE ' . Order::CART_INDEX . ' = ?', [$this->id]);
		DBOld::delete(Address::TABLE . ' WHERE ' . Address::CART_INDEX . ' = ?', [$this->id]);
	}

	/*
	* OLD
	*/
	public function removeCoupon() {
		if ($this->couponId) {
			DBOld::update("carts SET code='' WHERE id = ?", [$this->id]);

			foreach ($this->orders as $order) {
				$order->resetDiscount();
				$order->resetGain();
			}

			if ($this->coupon->freeShipping) {
				$this->updateShippingMethodId(ShippingMethod::PRIMARY_SHIPPING_METHOD_ID);
			}

			$this->data['couponCode'] = null;
			$this->data = [];
		}
	}

	/*
	* Misc methods
	*/
	public function advance(int|null $nextStepId = null) {
		if (!$nextStepId) {
			$currentStepId = ViewOld::get('currentStepId');

			if ($this->stepId == $currentStepId) {
				$nextStepId = $currentStepId + 1;

				$this->updateStepId($nextStepId);
			}
		} else {
			$this->updateStepId($nextStepId);
		}

		return $this;
	}

	public function advanceAndredirect_old(int|null $nextStepId = null) {
		$this->advance($nextStepId);

		redirect_old($this->openUrl);
	}

	public function confirm(bool $sendNotification = true): void {
		// update cart time
		DBOld::update(self::TABLE . ' SET time = NOW(), created_at = NOW() WHERE ' . self::INDEX . ' = ?', [$this->id]);

		// update seller's balance
		DBOld::update(Seller::TABLE . ' SET earned = earned + ? WHERE ' . Seller::INDEX . ' = ?', [$this->gain, $this->customerId]);

		if ($this->customer->isSeller) {
			DBOld::update(Seller::TABLE . ' SET credit = credit - ? WHERE ' . Seller::INDEX . ' = ?', [$this->usedCredit, $this->customerId]);
		}

		// remove used coupons
		if ($this->couponId) {
			if ($this->coupon->onePerCustomer) {
				DBOld::add('codes_used (coupon_id, user_id)', [$this->couponId, $this->customerId]);
			}

			if (!$this->coupon->permanent) {
				DBOld::delete(Coupon::TABLE . ' WHERE ' . Coupon::INDEX . ' = ?', [$this->couponId]);
			}
		}

		// process the individual orders
		$this->processOrders();

		// update the cart status and send the confirmation email
		$this->updateStatus('received', $sendNotification);
	}

	public function sendStatusEmail(bool $forceSend = false, bool $debug = false) {
		CartStatusUpdate::send($this, null, $forceSend, $debug);
	}

	public function sendTrackingEmail() {
		// CartTrackingUpdate::send($this);
	}

	public function processOrders(): void {
		foreach ($this->orders as $order) {
			$order = EcommerceOrder::find($order->id);

			$inventoryData = DBOld::row(
				'
				inventory.id as inventoryId
				FROM inventory
				INNER JOIN
					items_types ON inventory.type_id = items_types.id
				WHERE
					design_id = ? AND (type_id = ? OR same_as_type_id = ?) AND color_id = ? AND position_id = ? AND size_id = ?',
				[$order->design_id, $order->type_id, $order->type_id, $order->color_id, $order->position_id, $order->size_id]
			);

			if ($inventoryData) {
				$inventoryId = $inventoryData['inventoryId'];

				DBOld::update('orders SET inventory = 1 WHERE id = ?', [$order->id]);
				DBOld::delete('inventory WHERE id = ?', [$inventoryId]);
			}

			if ($this->customerId != $order->seller_id) {
				DBOld::update('stores SET credit = credit + ? WHERE user_id = ?', [($order->gain * .1), $order->seller_id]);
			}
		}
	}

	/*
	* OLD
	*/
	public function advanceStep() {
		$this->updateStepId($this->stepId + 1);
	}

	public function addCoupon(int $couponId = null) {
		$error = false;
		$coupon = new Coupon($couponId);

		if ($coupon->isValid) {
			// remove shipping cost if the coupon has free shipping
			if ($coupon->freeShipping) {
				$this->updateShippingMethodId(ShippingMethod::FREE_SHIPPING_METHOD_ID);
			}

			foreach ($this->orders as $order) {
				$sellerDiscount = 0;
				$ourDiscount = 0;

				// check if the order matches the coupon requirements
				$matchesDesign = !$coupon->design_id || $coupon->design_id == $order->design_id;
				$matchesSeller = !$coupon->seller_id || $coupon->seller_id == $order->seller_id;
				$matchesType = !$coupon->type_id || $coupon->type_id == $order->type_id;
				$matchesColor = !$coupon->color_id || $coupon->color_id == $order->color_id;

				// calculate the discount amount
				if ($matchesDesign && $matchesSeller && $matchesType && $matchesColor) {
					if (!$coupon->removesGain) {
						// precentage discount
						if ($coupon->ourPercentageDiscountMultiple && $this->quantity > 2) {
							$ourDiscountPercentage = $coupon->ourPercentageDiscountMultiple;
						} else if ($coupon->ourPercentageDiscountTwo && $this->quantity > 1) {
							$ourDiscountPercentage = $coupon->ourPercentageDiscountTwo;
						} else {
							$ourDiscountPercentage = $coupon->ourPercentageDiscount;
						}

						if ($coupon->sellerPercentageDiscountMultiple && $this->quantity > 2) {
							$sellerDiscountPercentage = $coupon->sellerPercentageDiscountMultiple;
						} else if ($coupon->sellerPercentageDiscountTwo && $this->quantity > 1) {
							$sellerDiscountPercentage = $coupon->sellerPercentageDiscountTwo;
						} else {
							$sellerDiscountPercentage = $coupon->sellerPercentageDiscount;
						}

						// fixed discount
						if ($coupon->ourFixedDiscountMultiple && $this->quantity > 1) {
							$ourDiscountFixed = $coupon->ourFixedDiscountMultiple;
						} else {
							$ourDiscountFixed = $coupon->ourFixedDiscount;
						}

						if ($coupon->sellerFixedDiscountMultiple && $this->quantity > 1) {
							$sellerDiscountFixed = $coupon->sellerFixedDiscountMultiple;
						} else {
							$sellerDiscountFixed = $coupon->sellerFixedDiscount;
						}

						// total discount
						$ourDiscount = ($order->fullPrice * $ourDiscountPercentage / 100) + $ourDiscountFixed;
						$sellerDiscount = ($order->fullPrice * $sellerDiscountPercentage / 100) + $sellerDiscountFixed;
					} else {
						$sellerDiscount = $order->gain;
					}

					// calculate total discount and recuced gain
					$totalDiscount = $ourDiscount + $sellerDiscount;
					$gainReduction = $coupon->removesGain ? $order->gain : $sellerDiscount;

					$order->updateDiscount($totalDiscount);
					$order->updateGainReduction($gainReduction);
				}
			}

			DBOld::update(self::TABLE . ' SET code = ? WHERE ' . self::INDEX . ' = ?', [$coupon->code, $this->id]);

			$this->data['couponCode'] = $coupon->code;
			$this->data = [];
		} else {
			$error = $coupon->error;
		}

		return $error;
	}
}
