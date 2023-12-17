<?php

namespace Ecommerce;

use DB;
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
		return  DB::count('items WHERE desID=? AND status=1', [$this->id]);
	}

	private function getMainProduct() {
		$productId = DB::find('ID FROM items WHERE desID=? AND status=1 ORDER BY typeID ASC', [$this->id]);

		return  new Product($productId);
	}

	private function getProducts() {
		$products = [];
		$productEntries = DB::select(Product::INDEX . ' FROM ' . Product::TABLE . ' WHERE ' . Product::DESIGN_INDEX . ' = ?', [$this->id]);

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
		return $this->sellerId == userId();
	}

	private function loved() {
		global $user;

		$loved = logged() && DB::exists('likes WHERE userID=? AND desID=?', [$user->id, $this->id]);

		return $loved;
	}

	public function getImage($size = 400) {
		$file = $size == 400 ? '/uploads/items/des/' . $this->id . '_m.jpg' : '/uploads/items/des/' . $this->id . '.jpg';

		if (!file_exists(public_server_path($file))) {
			des_img_gen($this);

			if (!file_exists(public_server_path($file))) {
				$file = '/app/assets/mockups/no-image';
			}
		}


		return $file;
	}

	public function getClientImage($size = 400) {
		return public_client_path($this->getImage($size));
	}

	private function getTags() {
		if ($this->id) {
			$tags = DB::find('tags FROM designs_tags WHERE desID = ?', [$this->id]);

			if ($tags) {
				return explode(',', $tags);
			}
		}

		return null;
	}

	private function getLogoGroupId() {
		return DB::find(self::LOGO_GROUP_INDEX . ' FROM ' . self::TABLE . ' WHERE ' . self::INDEX . ' = ?', [$this->id]);
	}

	private function getLogoGroup() {
		return new LogoGroup($this->logoGroupId);
	}

	private function getLogos() {
		return $this->logoGroup->logos;
	}

	public function delete(bool $permanently = false) {
		if ($permanently) {
			DB::delete('designs WHERE ID=?', [$this->id]);
			DB::delete('designs_tags WHERE desID=?', [$this->id]);
			DB::delete('likes WHERE desID=?', [$this->id]);
		} else {
			DB::update('designs SET status=0 WHERE ID=?', [$this->id]);
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
			DB::update(self::TABLE . ' SET name = ? WHERE ' . self::INDEX . ' = ?', [$name, $this->id]);
			DB::update(Product::TABLE . ' SET name = ? WHERE ' . Product::DESIGN_INDEX . ' = ?', [$name, $this->id]);

			unset($this->data['name']);
			unset($this->data['fullName']);
		}

		return $this;
	}
}
