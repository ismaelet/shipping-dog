<?php

namespace Ecommerce;

use DateTime;
use DB;
use Ecommerce\Design\Logo;
use Ecommerce\Product\Color;
use Ecommerce\Product\Size;
use DatabaseObject;

class Order {

	use DatabaseObject;

	public const TABLE = 'orders';
	public const INDEX = 'ID';
	public const CART_INDEX = 'cartID';
	public const PRODUCT_INDEX = 'itemID';
	public const SELLER_INDEX = 'sellerId';
	public const DESIGN_INDEX = 'designId';
	public const COLOR_INDEX = 'colorID';
	public const SIZE_INDEX = 'sizeID';
	public const POSITION_INDEX = 'posID';
	public const LOGO_INDEX = 'extID';
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

	private function getTimeString() {
		return (new DateTime($this->time))
			->format('Y-m-d');
	}

	private function getProduct() {
		return (new Product($this->productId))
			->setColorId($this->colorId)
			->setPositionId($this->positionId);
	}

	private function getDesign() {
		return new Design($this->designId);
	}

	private function getSeller() {
		return new Seller($this->sellerId);
	}

	private function getColor() {
		return new Color($this->colorId);
	}

	private function getCart() {
		return new Cart($this->cartId);
	}

	private function getSize() {
		return new Size($this->sizeId);
	}

	private function getLogo() {
		return new Logo($this->logoId);
	}

	private function getTypeId() {
		return $this->product->type->id;
	}

	private function getType() {
		return $this->product->type;
	}

	private function getDesignId() {
		return $this->product->designId;
	}
	private function getSellerId() {
		return $this->product->sellerId;
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

	private function getDescription() {
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

	/*
	* Set methods
	*/

	/*
	* Update methods
	*/
	public function updateDiscount(float $discountAmount) {
		$discountAmount = round($discountAmount, 2);

		DB::update('orders SET price_disc=? WHERE ID = ?', [$discountAmount, $this->id]);

		$this->data['fixedDiscount'] = $discountAmount;
		$this->data = [];

		return $this;
	}

	public function updateGainReduction(float $gainReduction) {
		$gainReduction = round($gainReduction, 2);

		DB::update('orders SET gain_disc=? WHERE ID = ?', [$gainReduction, $this->id]);

		$this->data['gainReduction'] = $gainReduction;
		unset($this->data['totalGain']);

		return $this;
	}

	/*
	* Instance methods
	*/
	public static function add($config) {
		$orderId = null;
		$productId = $config['itemId'];
		$colorId = $config['colorId'] ?? 0;
		$sizeId = $config['sizeId'] ?? 0;
		$genderId = $config['genderId'] ?? 0;
		$logoId = $config['logoId'] ?? 0;
		$positionId = $config['positionId'] ?? 1;
		$quantity = $config['quantity'] ?? 1;

		if ($productId) {
			$product = (new Product($productId))->setLogoId($logoId);
			$priceSize = DB::find('price FROM items_sizes WHERE ID = ?', [$sizeId]);
			$price = $product->fullPrice + $priceSize;

			if (userId()) {
				$cartId = DB::find('ID FROM carts WHERE buyer=? AND status=0', [userId()]) ?: DB::add('carts (buyer) VALUES (?)', [userId()]);
			} else {
				$cartId = $_SESSION['cartId'] ?? $_SESSION['cartId'] = DB::add('carts () VALUES ()');
			}

			$cart = new Cart($cartId);

			// add order
			$orderId = DB::add(
				'orders (buyer, cartID, itemID, sellerId, designId, typeID, colorID, sizeID, gendID, posID, extID, fullProductPrice, price_disc, productGain, quantity)',
				[userId() ?: null, $cart->id, $product->id, $product->sellerId, $product->designId, $product->type->id, $colorId, $sizeId, $genderId, $positionId, $logoId, $price, $product->customerDiscount, $product->customerGain, $quantity]
			);

			DB::update(Cart::TABLE . ' SET quantity = quantity + ? WHERE ' . Cart::INDEX . ' = ?', [$quantity, $cart->id]);

			$cart->resetDynamicProperties();

			if ($cart->couponId) {
				$couponId = $cart->couponId;
				$cart->removeCoupon();
				$cart->addCoupon($couponId);
			}
		}

		redirect('/cart?step-id=2');

		return new self($orderId);
	}

	public function delete() {
		DB::delete(self::TABLE . ' WHERE ' . self::INDEX . ' = ?', [$this->id]);
		DB::update(Cart::TABLE . ' SET quantity = quantity - ? WHERE ' . Cart::INDEX . ' = ?', [$this->quantity, $this->cart->id]);

		$this->cart->resetDynamicProperties();
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
