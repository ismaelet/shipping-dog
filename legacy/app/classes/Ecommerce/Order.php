<?php

namespace Ecommerce;

use App\Models\Ecommerce\Cart;
use DateTime;
use DBOld;
use Ecommerce\Design\Logo;
use Ecommerce\Product\Color;
use Ecommerce\Product\Size;
use DatabaseObject;
use Ecommerce\Cart as OldCart;
use Illuminate\Support\Facades\Auth;

class Order {

	use DatabaseObject;

	public const TABLE = 'orders';
	public const INDEX = 'id';
	public const CART_INDEX = 'cart_id';
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

	private function getTimeString() {
		return (new DateTime($this->time))
			->format('Y-m-d');
	}

	private function getProduct() {
		return (new Product($this->productId))
			->setColorId($this->colorId)
			->setPositionId($this->position_id);
	}

	private function getDesign() {
		return new Design($this->design_id);
	}

	private function getSeller() {
		return new Seller($this->seller_id);
	}

	private function getColor() {
		return new Color($this->colorId);
	}

	private function getCart() {
		return new OldCart($this->cart_id);
	}

	private function getSize() {
		return new Size($this->size_id);
	}

	private function getLogo() {
		return new Logo($this->logo_id);
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

	/*
	* Set methods
	*/

	/*
	* Update methods
	*/
	public function updateDiscount(float $discountAmount) {
		$discountAmount = round($discountAmount, 2);

		DBOld::update('orders SET price_disc=? WHERE id = ?', [$discountAmount, $this->id]);

		$this->data['fixedDiscount'] = $discountAmount;
		$this->data = [];

		return $this;
	}

	public function updateGainReduction(float $gainReduction) {
		$gainReduction = round($gainReduction, 2);

		DBOld::update('orders SET gain_disc=? WHERE id = ?', [$gainReduction, $this->id]);

		$this->data['gainReduction'] = $gainReduction;
		unset($this->data['totalGain']);

		return $this;
	}

	/*
	* Instance methods
	*/
	public static function add($config) {
		$orderId = null;
		$productId = $config['product_id'];
		$colorId = $config['colorId'] ?? 0;
		$size_id = $config['size_id'] ?? 0;
		$genderId = $config['genderId'] ?? 0;
		$logo_id = $config['logo_id'] ?? 0;
		$position_id = $config['position_id'] ?? 1;
		$quantity = $config['quantity'] ?? 1;

		if ($productId) {
			$product = (new Product($productId))->setLogoId($logo_id);
			$priceSize = DBOld::find('price FROM items_sizes WHERE id = ?', [$size_id]);
			$price = $product->fullPrice + $priceSize;

			if (Auth::check()) {
				$cart = Cart::where('user_id', Auth::id())->where('status', 0)->first();

				if (!$cart) {
					$cart = Cart::create(['user_id' => Auth::id()]);
				}
			} else {
				if (isset($_SESSION['cart_id'])) {
					$cart = Cart::find($_SESSION['cart_id']);
				} else {
					$cart = Cart::create();
					$_SESSION['cart_id'] = $cart->id;
				}
			}

			$oldCart = new OldCart($cart->id);

			// add order
			$orderId = DBOld::add(
				'orders (user_id, cart_id, product_id, seller_id, design_id, type_id, color_id, size_id, gender_id, position_id, logo_id, fullProductPrice, price_disc, productGain, quantity)',
				[Auth::id() ?: null, $cart->id, $product->id, $product->seller_id, $product->design_id, $product->type->id, $colorId, $size_id, $genderId, $position_id, $logo_id, $price, $product->customerDiscount, $product->customerGain, $quantity]
			);

			DBOld::update(OldCart::TABLE . ' SET quantity = quantity + ? WHERE ' . OldCart::INDEX . ' = ?', [$quantity, $cart->id]);

			if ($oldCart->couponId) {
				$couponId = $oldCart->couponId;
				$oldCart->removeCoupon();
				$oldCart->addCoupon($couponId);
			}
		}

		redirect_old('/cart?step-id=2');

		return new self($orderId);
	}

	public function delete() {
		DBOld::delete(self::TABLE . ' WHERE ' . self::INDEX . ' = ?', [$this->id]);
		DBOld::update(OldCart::TABLE . ' SET quantity = quantity - ? WHERE ' . OldCart::INDEX . ' = ?', [$this->quantity, $this->cart->id]);

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
