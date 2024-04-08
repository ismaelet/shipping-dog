<?php

namespace Ecommerce;

use DBOld;
use Ecommerce\Design\LogoGroup;
use Ecommerce\Product;
use DatabaseObject;

/**
 * Design class
 * 
 * @property string image
 * @property string clientImage
 */
class Design {

	use DatabaseObject;

	public const TABLE = 'designs';
	public const INDEX = 'ID';
	public const SELLER_INDEX = 'userID';
	public const LOGO_GROUP_INDEX = 'extFID';

	private function getId() {
		return $this->databaseEntry[self::INDEX];
	}

	private function getSellerId() {
		return $this->databaseEntry[self::SELLER_INDEX];
	}

	private function getFullName() {
		return  $this->id . ' - ' . $this->name;
	}

	private function getUrl() {
		return $this->mainProduct->url;
	}

	private function getClientUrl() {
		return client_url($this->url);
	}

	private function getProductCount() {
		return  DBOld::count('items WHERE desID = ? AND status = 1', [$this->id]);
	}

	private function getMainProduct() {
		$productId = DBOld::find('ID FROM items WHERE desID = ? AND status = 1 ORDER BY typeID ASC', [$this->id]);

		return  new Product($productId);
	}

	private function getProducts() {
		$products = [];
		$productEntries = DBOld::select(Product::INDEX . ' FROM ' . Product::TABLE . ' WHERE ' . Product::DESIGN_INDEX . ' = ?', [$this->id]);

		while ($productEntry = $productEntries->fetch_assoc()) {
			$products[] = new Product($productEntry[Product::INDEX]);
		}

		return $products;
	}

	private function getSeller() {
		return  new Seller($this->sellerId);
	}

	private function getLoved() {
		return  $this->loved();
	}

	private function getItem() {
		return $this->mainProduct;
	}

	private function getItemN() {
		return $this->productCount;
	}

	private function getUserIsOwner() {
		return $this->sellerId == auth()->id();
	}

	private function loved() {
		global $user;

		$loved = auth()->check() && DBOld::exists('likes WHERE userID = ? AND desID = ?', [auth()->id(), $this->id]);

		return $loved;
	}

	public function getImage($size = 400): string {
		$file = $size == 400 ? '/designs/previews/' . $this->id . '_m.jpg' : '/designs/previews/' . $this->id . '.jpg';

		if (file_exists(public_storage_path($file))) {
			return $file;
		}

		des_img_gen($this->id);

		if (file_exists(public_storage_path($file))) {
			return $file;
		}

		return '/product_types/mockups/no-image';
	}

	public function getClientImage($size = 400) {
		return public_asset($this->getImage($size));
	}

	private function getTags() {
		if ($this->id) {
			$tags = DBOld::find('tags FROM designs_tags WHERE desID = ?', [$this->id]);

			if ($tags) {
				return explode(',', $tags);
			}
		}

		return null;
	}

	private function getLogoGroupId() {
		return DBOld::find(self::LOGO_GROUP_INDEX . ' FROM ' . self::TABLE . ' WHERE ' . self::INDEX . ' = ?', [$this->id]);
	}

	private function getLogoGroup() {
		return new LogoGroup($this->logoGroupId);
	}

	private function getLogos() {
		return $this->logoGroup->logos;
	}

	public function delete(bool $permanently = false) {
		if ($permanently) {
			DBOld::delete('designs WHERE ID = ?', [$this->id]);
			DBOld::delete('designs_tags WHERE desID = ?', [$this->id]);
			DBOld::delete('likes WHERE desID = ?', [$this->id]);
		} else {
			DBOld::update('designs SET status=0 WHERE ID = ?', [$this->id]);
		}

		foreach ($this->products as $product) {
			$product->delete($permanently);
		}

		$this->data = [];
		$this->__construct($this->id);

		return $this;
	}

	/**
	 * Update methods
	 */
	public function updateName(string $name) {
		if ($name) {
			DBOld::update(self::TABLE . ' SET name = ? WHERE ' . self::INDEX . ' = ?', [$name, $this->id]);
			DBOld::update(Product::TABLE . ' SET name = ? WHERE ' . Product::DESIGN_INDEX . ' = ?', [$name, $this->id]);

			unset($this->data['name']);
			unset($this->data['fullName']);
		}

		return $this;
	}
}
