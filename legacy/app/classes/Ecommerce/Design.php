<?php

namespace Ecommerce;

use App\Models\Ecommerce\Design\LogoGroup as DesignLogoGroup;
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
	public const INDEX = 'id';
	public const SELLER_INDEX = 'user_id';
	public const LOGO_GROUP_INDEX = 'logo_group_id';

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
		return  DBOld::count('items WHERE design_id = ? AND status = 1', [$this->id]);
	}

	private function getMainProduct() {
		$productId = DBOld::find('id FROM items WHERE design_id = ? AND status = 1 ORDER BY type_id ASC', [$this->id]);

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
		return  new Seller($this->seller_id);
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
		return $this->seller_id == auth()->id();
	}

	private function loved() {
		global $user;

		$loved = auth()->check() && DBOld::exists('likes WHERE user_id = ? AND design_id = ?', [auth()->id(), $this->id]);

		return $loved;
	}

	public function getImage($size = 400): string {
		$file = $size == 400 ? '/designs/previews/' . $this->id . '_m.jpg' : '/designs/previews/' . $this->id . '.jpg';

		if (file_exists(public_storage($file))) {
			return $file;
		}

		des_img_gen($this->id);

		if (file_exists(public_storage($file))) {
			return $file;
		}

		return '/product_types/mockups/no-image';
	}

	public function getClientImage($size = 400) {
		return public_asset($this->getImage($size));
	}

	private function getTags() {
		if ($this->id) {
			$tags = DBOld::find('tags FROM designs_tags WHERE design_id = ?', [$this->id]);

			if ($tags) {
				return explode(',', $tags);
			}
		}

		return null;
	}

	private function getLogoGroupId(): ?int {
		return DesignLogoGroup::where('id', $this->logo_group_id)->value('id');
	}

	private function getLogoGroup(): ?DesignLogoGroup {
		return DesignLogoGroup::where('id', $this->logo_group_id)->first();
	}

	private function getLogos() {
		return $this->logoGroup->logos;
	}

	public function delete(bool $permanently = false) {
		if ($permanently) {
			DBOld::delete('designs WHERE id = ?', [$this->id]);
			DBOld::delete('designs_tags WHERE design_id = ?', [$this->id]);
			DBOld::delete('likes WHERE design_id = ?', [$this->id]);
		} else {
			DBOld::update('designs SET status=0 WHERE id = ?', [$this->id]);
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
