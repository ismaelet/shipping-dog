<?php

namespace Ecommerce;

use DB;
use Ecommerce\Product\Type;
use Ecommerce\Product\Color;
use Ecommerce\Product\Position;
use Ecommerce\Product\Preview;
use DatabaseObject;
use Ecommerce\Design\Logo;

/**
 * Class Product
 * 
 * @property int statusId
 * 
 * @property boolean isUserOwner
 * @property boolean deleted
 * 
 * @property string specificCoverImage
 * @property bool hasSpecificCoverImage
 * 
 * @property float price
 * @property float fullProductPrice
 * @property float fullPrice
 * @property float basePrice
 * @property float whiteGain
 * @property float coloredGain
 * @property string formattedPrice
 * @property string priceString
 * @property string formattedFullPrice
 * @property string fullPriceString
 */
class Product {

	use DatabaseObject;

	public const TABLE = 'items';
	public const INDEX = 'ID';
	public const DESIGN_INDEX = 'desID';
	public const SELLER_INDEX = 'userID';
	public const TYPE_INDEX = 'typeID';
	public const COLOR_INDEX = 'colorID';

	/*
	* Get methods
	*/
	private function getId() {
		return $this->databaseEntry[self::INDEX];
	}

	private function getName() {
		return $this->databaseEntry['name'] ?: $this->design->name;
	}

	private function getUrl() {
		return '/shop/item?id=' . $this->id;
	}

	private function getClientUrl() {
		return public_client_url($this->url);
	}

	private function getFullUrl() {
		return $this->url . '&colorId=' . $this->color->id . '&positionId=' . $this->position->id;
	}

	private function getClientFullUrl() {
		return public_client_url($this->fullUrl);
	}

	private function getDesignId() {
		return $this->databaseEntry[self::DESIGN_INDEX];
	}

	private function getDesign() {
		return new Design($this->designId);
	}

	private function getSellerId() {
		return $this->databaseEntry[self::SELLER_INDEX];
	}

	private function getSeller() {
		return new Seller($this->sellerId);
	}

	private function getTypeId() {
		return $this->databaseEntry[self::TYPE_INDEX];
	}

	private function getType() {
		return new Type($this->typeId);
	}

	private function getColorId() {
		return $this->databaseEntry[self::COLOR_INDEX];
	}

	private function getColor() {
		return new Color($this->colorId);
	}

	private function getGenderId() {
		return $this->type->genderId;
	}

	private function getGender() {
		return $this->type->gender;
	}

	private function getAvailableColorSlugs() {
		return explode(',', $this->colors);
	}

	private function getPositionId() {
		return $this->availablePositionIds[0] ?: ($this->type->availablePositionIds[0] ?? 1);
	}

	private function getPosition() {
		return new Position($this->positionId);
	}

	private function getAvailablePositionIds() {
		return explode(',', $this->positions);
	}

	private function getLogoId() {
		return 0;
	}

	private function getLogo() {
		return new Logo($this->logoId);
	}

	private function getLogoGroup() {
		$logoGroup = false;

		if (in_array($this->type->id, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11])) {
			$logoGroup = $this->design->logoGroup;
		}

		return $logoGroup;
	}

	private function getHasLogos() {
		return (bool) $this->logoGroup && $this->logoGroup->id;
	}

	private function getLogos() {
		$logos = null;

		if ($this->logoGroup) {
			$logos = $this->logoGroup->logos;
		}

		return $logos;
	}

	private function getStatusId() {
		return $this->status;
	}

	private function getIsCustom() {
		return (bool) $this->designId ? $this->design->custom : true;
	}

	private function getCoverImage() {
		switch (true) {
			case file_exists(public_server_path($this->specificCoverImage)):
				$coverImage = $this->specificCoverImage;
				break;
			case !$this->isCustom && file_exists(public_server_path($file = '/uploads/product_types/covers/' . $this->type->slug)):
				$coverImage = $file;
				break;
			case !$this->isCustom && file_exists(public_server_path($file = '/uploads/product_types/covers/' . $this->type->slug . '.jpg')):
				$coverImage = $file;
				break;
			default:
				$coverImage = null;
		}

		return $coverImage;
	}

	private function getClientCoverImage() {
		return public_client_path($this->coverImage);
	}

	private function getSpecificCoverImage() {
		return '/uploads/products/' . $this->id . '-cover';
	}

	private function getHasSpecificCoverImage() {
		return file_exists(public_server_path($this->specificCoverImage));
	}

	public function getImage(int $imageSize = null) {
		return (new Preview($this, $imageSize))
			->getImage();
	}

	public function getSpecificImage(int $imageSize = null) {
		return (new Preview($this, $imageSize))
			->filePath;
	}

	public function getClientImage(int $imageSize = null) {
		return public_client_path($this->getImage($imageSize));
	}

	public function getServerSpecificImage(int $imageSize = null) {
		return public_server_path($this->getSpecificImage($imageSize));
	}

	public function getHasImage(int $imageSize = null) {
		return (new Preview($this, $imageSize))
			->hasImage;
	}

	public function getFullImage() {
		return (new Preview($this, Preview::BIG_IMAGE_SIZE))
			->getImage();
	}

	public function getClientFullImage() {
		return public_client_path($this->fullImage);
	}

	private function getPriceData() {
		$priceData =
			DB::row('* FROM items_prices WHERE itemID = ?', [$this->id]) ?: (DB::row('* FROM prices_stores WHERE storeID = ? AND typeID = ?', [$this->seller->id, $this->type->id]) ?:
				DB::row('* FROM prices_default WHERE typeID = ?', [$this->type->id])
			);

		return $priceData;
	}

	private function getPrice() {
		return $this->fullPrice - $this->customerDiscount;
	}

	private function getFormattedPrice() {
		return format($this->price);
	}

	private function getPriceString() {
		return format_price($this->price);
	}

	private function getFullProductPrice() {
		$productPrice = 0;

		if ($this->id && isset($this->priceData['price'])) {
			$productPrice = $this->priceData['price'];
		} else {
			error_log('No valid price for product ' . $this->id);
		}

		return $productPrice;
	}

	private function getFullPrice() {
		return $this->fullProductPrice + $this->logoPrice;
	}

	private function getLogoPrice() {
		return $this->logo->price;
	}

	private function getFullPriceString() {
		return format_price($this->fullPrice);
	}

	private function getFormattedFullPrice() {
		return format($this->fullPrice);
	}

	private function getBasePrice() {
		return $this->priceData['price'] - $this->priceData['gain_w'] / .75;
	}

	private function getBasePriceString() {
		return format_price($this->basePrice);
	}

	private function getGain() {
		return $this->color->dark ? $this->coloredGain : $this->whiteGain;
	}

	private function getWhiteGain() {
		return $this->priceData['gain_w'];
	}

	private function getColoredGain() {
		return $this->priceData['gain_c'];
	}

	private function getCustomerDiscount() {
		return $this->isUserOwner ? $this->fullProductPrice - $this->basePrice : 0;
	}

	private function getCustomerGain() {
		return $this->isUserOwner ? 0 : $this->gain;
	}

	private function getIsUserOwner() {
		return $this->sellerId == userId();
	}

	/*
	* Set methods
	*/
	public function setColorId(int $colorId = null) {
		$this->data['colorId'] = $colorId;

		unset($this->data['color']);

		return $this;
	}

	public function setLogoId(int $logoId = null) {
		$this->data['logoId'] = $logoId;

		unset($this->data['logo']);

		return $this;
	}

	public function setPositionId(int $positionId = null) {
		$this->data['positionId'] = $positionId;

		unset($this->data['position']);

		return $this;
	}

	public function setStatusId(int $statusId) {
		$this->data['statusId'] = $statusId;

		return $this;
	}

	/*
	* Update methods
	*/
	public function updateColors(array $availableColorSlugs) {
		$availableColorSlugsString = implode(',', $availableColorSlugs);

		DB::update('items SET colors=? WHERE ID = ?', [$availableColorSlugsString, $this->id]);

		$this->data['availableColorSlugs'] = $availableColorSlugs;

		return $this;
	}

	public function updateColorId(int $colorId) {
		$oldColorId = $this->colorId;

		$this->setColorId($colorId);

		if ($this->colorId != $oldColorId) {
			DB::update('items SET colorID = ? WHERE ID = ?', [$colorId, $this->id]);
		}

		return $this;
	}

	public function updateStatus(bool $published) {
		$statusId = $published ? 1 : 0;

		DB::update('items SET status=? WHERE ID = ?', [$statusId, $this->id]);

		$this->setStatusId($published);

		return $this;
	}

	public function updatePositionIds(mixed $positionIds) {
		if (is_array($positionIds)) {
			$positionIds = implode(',', $positionIds);
		}

		DB::update('items SET positions=? WHERE ID = ?', [$positionIds, $this->id]);

		$this->data['positionIds'] = $positionIds;

		unset($this->positionId);

		return $this;
	}

	public function updatePrice(float $newPrice) {
		$priceDifference = $newPrice - $this->fullPrice;
		$newWhiteGain = $priceDifference * 0.75 + $this->whiteGain;
		$newColoredGain = $priceDifference * 0.75 + $this->coloredGain;

		DB::update('items_prices SET price = ?, gain_w = ?, gain_c = ? WHERE itemID = ?', [$newPrice, $newWhiteGain, $newColoredGain, $this->id]);

		return $this;
	}

	/*
	* Instance methods
	*/
	static function new(int $designId, int $typeId) {
		$design = new Design($designId);
		$type = new Type($typeId);
		$productId = DB::find(self::INDEX . ' FROM ' . self::TABLE . ' WHERE ' . self::DESIGN_INDEX . ' = ? AND ' . self::TYPE_INDEX . ' = ?', [$design->id, $type->id]);

		if (!$productId) {
			//get colors
			$colors = [];
			$availableColorSlugs = [];

			foreach ($type->publicColors as $color) {
				$colors[] = $color;
				$availableColorSlugs[] = $color->slug;
			}

			$colorId = isset($colors[0]) ? $colors[0]->id : 0;
			$availableColorSlugs = implode(',', $availableColorSlugs);

			//get positions
			$positions = $type->id != 114 || $design->sellerId == 5151 ? implode(',', $type->availablePositionIds) : '3,4';

			//create item
			$productId = DB::add(
				self::TABLE . ' (name, ' . self::SELLER_INDEX . ', ' . self::DESIGN_INDEX . ', ' . self::TYPE_INDEX . ', ' . self::COLOR_INDEX . ', colors, positions, status)',
				[$design->name, $design->sellerId, $design->id, $type->id, $colorId, $availableColorSlugs, $positions, 1]
			);
		}

		$product = new self($productId);

		return $product;
	}

	public function delete(bool $permanently = false) {
		if ($permanently || $this->isCustom) {
			DB::delete(self::TABLE . ' WHERE ' . self::INDEX . ' = ?', [$this->id]);
			DB::delete('items_info WHERE itemID = ?', [$this->id]);
			DB::delete('items_previews WHERE itemID = ?', [$this->id]);
			DB::delete('items_prices WHERE itemID = ?', [$this->id]);
		} else {
			DB::update(self::TABLE . ' SET deleted = 1 WHERE ' . self::INDEX . ' = ?', [$this->id]);
		}

		$this->reset();
	}

	/*
	* Render methods
	*/

	/*
	* Misc methods
	*/
}
