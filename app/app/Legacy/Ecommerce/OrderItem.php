<?php

namespace App\Legacy\Ecommerce;

use Legacy\System\Traits\DatabaseObject;
use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\OrderItem as NewOrderItem;
use App\Models\Ecommerce\Product;
use DateTime;
use App\Legacy\Ecommerce\Design\Logo as LegacyLogo;
use App\Legacy\Ecommerce\Product\Color as LegacyProductColor;
use App\Legacy\Ecommerce\Product\Size as LegacyProductSize;
use App\Legacy\Ecommerce\OrderItem as LegacyOrderItem;
use Illuminate\Support\Facades\Auth;
use App\Legacy\Ecommerce\Order as LegacyOrder;

class OrderItem {

	use DatabaseObject;

	public const TABLE = 'order_items';
	public const INDEX = 'id';
	public const CART_INDEX = 'order_id';
	public const PRODUCT_INDEX = 'product_id';
	public const SELLER_INDEX = 'seller_id';
	public const DESIGN_INDEX = 'design_id';
	public const COLOR_INDEX = 'color_id';
	public const SIZE_INDEX = 'size_id';
	public const POSITION_INDEX = 'position_id';
	public const LOGO_INDEX = 'logo_id';
	public const PRODUCT_GAIN_FIELD = 'productGain';

	/*
	* Get methods
	*/
	private function getId() {
		return $this->databaseEntry[self::INDEX];
	}

	private function getCartId() {
		return $this->databaseEntry[self::CART_INDEX];
	}

	private function getProductId() {
		return $this->databaseEntry[self::PRODUCT_INDEX];
	}

	private function getColorId() {
		return $this->databaseEntry[self::COLOR_INDEX];
	}

	private function getSizeId() {
		return $this->databaseEntry[self::SIZE_INDEX];
	}

	private function getPositionId() {
		return $this->databaseEntry[self::POSITION_INDEX];
	}

	private function getLogoId() {
		return $this->databaseEntry[self::LOGO_INDEX];
	}

	private function getFullPrice() {
		return $this->fullProductPrice * $this->quantity;
	}

	private function getFixedDiscount() {
		return $this->price_disc;
	}

	private function getGainReduction() {
		return $this->gain_disc;
	}

	private function getcreatedAtString() {
		return (new DateTime($this->time))
			->format('Y-m-d');
	}

	private function getProduct() {
		return (new Product($this->productId))
			->setColorId($this->color_id)
			->setPositionId($this->position_id);
	}

	private function getDesign() {
		return new Design($this->design_id);
	}

	private function getSeller() {
		return new Seller($this->seller_id);
	}

	private function getColor() {
		return new LegacyProductColor($this->color_id);
	}

	private function getOrder() {
		return new LegacyOrder($this->order_id);
	}

	private function newOrder() {
		return Order::find($this->order_id);
	}

	private function newOrderItem() {
		return NewOrderItem::find($this->id);
	}

	private function getSize() {
		return new LegacyProductSize($this->size_id);
	}

	private function getLogo() {
		return new LegacyLogo($this->logo_id);
	}

	private function getTypeId() {
		return $this->product->type->id;
	}

	private function getType() {
		return $this->product->type;
	}

	private function getDesignId() {
		return $this->product->design_id;
	}
	private function getSellerId() {
		return $this->product->seller_id;
	}

	private function getPrice() {
		return $this->productPrice * $this->quantity;
	}

	private function getFormattedPrice() {
		return format($this->price);
	}

	private function getPriceString() {
		return format_price($this->price);
	}

	private function getFormattedFullPrice() {
		return format($this->fullPrice);
	}

	private function getFullPriceString() {
		return format_price($this->fullPrice);
	}

	private function getProductPrice() {
		return $this->fullProductPrice - $this->discount;
	}

	private function getGain() {
		return ($this->productGain - $this->gainReduction) * $this->quantity;
	}

	private function getGainString() {
		return format_price($this->gain);
	}

	private function getDiscount() {
		return $this->payedWithCredit ? $this->fullPrice : $this->fixedDiscount;
	}

	private function getPayedWithCredit() {
		return (bool) $this->credit;
	}

	private function getName() {
		$name = $this->quantity > 1 ? $this->quantity . 'x ' : '';
		$name .= $this->product->statusId ? $this->product->name : 'Articolo rimosso';

		return $name;
	}

	public function getDescription(bool $showDeleted = false): string {
		$product = $this->product;

		if ($product->statusId || $showDeleted) {
			$description = $product->name;
			$description .= ' (' . $product->type->name;
			if ($product->type->sibling && !$product->type->unisex) $description .= ' ' . $product->gender->name;
			if (count($product->type->sizes) > 1) $description .= ' ' . $this->size->name;
			if (count($product->type->colors) > 1) $description .= ' ' . $product->color->name;
			if ($this->logo->name) $description .= ' ' . $this->logo->name;
			if ($product->position->id > 1) $description .= ' ' . $product->position->name;
			$description .= ')';
		} else {
			$description = 'Articolo rimosso';
		}

		return $description;
	}

	private function getFullDescription() {
		$product = $this->product;

		if ($product->statusId) {
			$description = $product->type->name;
			if ($product->type->sibling && !$product->type->unisex) $description .= ' - ' . $product->gender->name;
			$description .= '<br>';
			$description .= 'Taglia: ' . $this->size->name . '<br>';
			if ($product->color->name) $description .= 'Colore: ' . $product->color->name . '<br>';
			if ($product->position->id > 1) $description .= $product->position->name . '<br>';
			$description .= $this->logo->name;
		} else {
			$description = 'Articolo rimosso';
		}

		return $description;
	}

	private function getAdminDescription() {
		$product = $this->product;

		if ($product->statusId) {
			$description = $product->name;
			$description .= ' (' . $product->type->name;
			if ($product->type->sibling && !$product->type->unisex) $description .= ' ' . $product->gender->name;
			if (count($product->type->sizes) > 1) $description .= ' ' . $this->size->name;
			if (count($product->type->colors) > 1) $description .= ' ' . $product->color->name;
			if ($this->logo->name) $description .= ' ' . $this->logo->name;
			if ($product->position->id > 1) $description .= ' ' . $product->position->name;
			$description .= ')';
		} else {
			$description = 'Articolo rimosso';
		}

		return $description;
	}

	public function updateDiscount(float $discountAmount) {
		$discountAmount = round($discountAmount, 2);

		DB::update('order_items SET price_disc=? WHERE id = ?', [$discountAmount, $this->id]);

		$this->data['fixedDiscount'] = $discountAmount;
		$this->data = [];
		$this->newOrder()->updatePrice();

		return $this;
	}

	public function updateGainReduction(float $gainReduction) {
		$gainReduction = round($gainReduction, 2);

		DB::update('order_items SET gain_disc=? WHERE id = ?', [$gainReduction, $this->id]);

		$this->data['gainReduction'] = $gainReduction;
		unset($this->data['totalGain']);

		$this->newOrderItem()->updateGain();

		return $this;
	}

	/*
	* Instance methods
	*/
	public static function add($config) {
		$orderItemId = null;
		$productId = $config['product_id'];
		$color_id = $config['color_id'] ?? 0;
		$size_id = $config['size_id'] ?? 0;
		$genderId = $config['genderId'] ?? 0;
		$logo_id = $config['logo_id'] ?? 0;
		$position_id = $config['position_id'] ?? 1;
		$quantity = $config['quantity'] ?? 1;

		if ($productId) {
			$product = Product::find($productId);
			$product->color_id = $color_id;
			$product->position_id = $position_id;
			$product->size_id = $size_id;
			$product->logo_id = $logo_id;

			$priceSize = DB::find('price FROM items_sizes WHERE id = ?', [$size_id]);
			$price = $product->fullPrice() + $priceSize;

			if (Auth::check()) {
				$order = Order::where('user_id', Auth::id())->where('status', 0)->first();

				if (!$order) {
					$order = Order::create(['user_id' => Auth::id()]);
				}
			} else {
				if (isset($_SESSION['order_id'])) {
					$order = Order::find($_SESSION['order_id']);

					if (!$order) {
						$order = Order::create();
						$_SESSION['order_id'] = $order->id;
					}
				} else {
					$order = Order::create();
					$_SESSION['order_id'] = $order->id;
				}
			}

			$LegacyOrder = new LegacyOrder($order->id);

			// add order
			$orderItemId = DB::add(
				'order_items (user_id, order_id, product_id, seller_id, design_id, type_id, color_id, size_id, gender_id, position_id, logo_id, fullProductPrice, price_disc, productGain, quantity)',
				[Auth::id() ?: null, $order->id, $product->id, $product->user_id, $product->design_id, $product->type->id, $color_id, $size_id, $genderId, $position_id, $logo_id, $price, $product->customerDiscount(), $product->sellerGain(), $quantity]
			);

			$order->updateOrderItems();

			if ($LegacyOrder->couponId) {
				$couponId = $LegacyOrder->couponId;
				$LegacyOrder->removeCoupon();
				$LegacyOrder->addCoupon($couponId);
			}
		}

		redirect_old('/cart?step-id=2');

		return new self($orderItemId);
	}

	public function delete() {
		DB::delete(self::TABLE . ' WHERE ' . self::INDEX . ' = ?', [$this->id]);
		DB::update(LegacyOrderItem::TABLE . ' SET quantity = quantity - ? WHERE ' . LegacyOrderItem::INDEX . ' = ?', [$this->quantity, $this->order->id]);

		$this->newOrder()->updateOrderItems();

		$this->order->resetDynamicProperties();
	}

	/*
	* Misc methods
	*/
	public function resetDiscount() {
		$this->updateDiscount($this->product->customerDiscount);

		return $this;
	}

	public function resetGain() {
		$this->updateGainReduction(0);

		return $this;
	}
}
