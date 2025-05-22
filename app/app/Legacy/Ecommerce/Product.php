<?php

namespace App\Legacy\Ecommerce;

use App\Helpers\PreviewHelper;
use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Product as NewProduct;
use App\Models\Ecommerce\Product\ProductType;
use App\Legacy\Ecommerce\Product\Type as LegacyProductType;
use App\Legacy\Ecommerce\Product\Color as LegacyProductColor;
use App\Legacy\Ecommerce\Product\Position as LegacyProductPosition;
use App\Legacy\Ecommerce\Product\Preview as LegacyPreview;
use App\Legacy\Ecommerce\Design\Logo as LegacyLogo;
use Illuminate\Database\Eloquent\Collection;
use App\Legacy\Ecommerce\Design as LegacyDesign;

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

	use \Legacy\System\Traits\DatabaseObject;

	public const TABLE = 'products';
	public const INDEX = 'id';
	public const DESIGN_INDEX = 'design_id';
	public const SELLER_INDEX = 'user_id';
	public const TYPE_INDEX = 'type_id';
	public const COLOR_INDEX = 'color_id';

	/*
	* Get methods
	*/
	private function getId() {
		return $this->databaseEntry[self::INDEX];
	}

	private function getName() {
		return $this->databaseEntry['name'] ?: $this->design->name;
	}

	private function getUrl(): string {
		return $this->getNewProduct()->url();
	}

	private function getClientUrl() {
		return public_client_url($this->url);
	}

	private function getFullUrl() {
		return $this->url . '&color_id=' . $this->color->id . '&position_id=' . $this->position->id;
	}

	private function getClientFullUrl() {
		return public_client_url($this->fullUrl);
	}

	private function getDesignId() {
		return $this->databaseEntry[self::DESIGN_INDEX];
	}

	private function getDesign() {
		return new LegacyDesign($this->design_id);
	}

	private function getSellerId() {
		return $this->databaseEntry[self::SELLER_INDEX];
	}

	private function getSeller() {
		return new Seller($this->seller_id);
	}

	private function getTypeId() {
		return $this->databaseEntry[self::TYPE_INDEX];
	}

	private function getType() {
		return new LegacyProductType($this->type_id);
	}

	private function getColorId() {
		return $this->databaseEntry[self::COLOR_INDEX];
	}

	private function getColor() {
		return new LegacyProductColor($this->color_id);
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
		return new LegacyProductPosition($this->position_id);
	}

	private function getAvailablePositionIds() {
		return explode(',', $this->positions);
	}

	private function getLogoId() {
		return 0;
	}

	private function getLogo() {
		return new LegacyLogo($this->logo_id);
	}

	private function getLogoGroup() {
		$logoGroup = false;

		if (in_array($this->type->id, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11])) {
			$logoGroup = $this->design->logoGroup;
		}

		return $logoGroup;
	}

	private function getHasLogos(): bool {
		return (bool) $this->logoGroup && $this->logoGroup->id;
	}

	private function getLogos(): ?Collection {
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
		return (bool) $this->design_id ? $this->design->custom : true;
	}

	private function getCoverImage() {
		switch (true) {
			case file_exists(public_storage($this->specificCoverImage)):
				$coverImage = $this->specificCoverImage;
				break;
			case !$this->isCustom && file_exists(public_storage($file = '/product_types/covers/' . $this->type->slug . '.webp')):
				$coverImage = $file;
				break;
			case !$this->isCustom && file_exists(public_storage($file = '/product_types/covers/' . $this->type->slug . '.jpg')):
				$coverImage = $file;
				break;
			default:
				$coverImage = null;
		}

		return $coverImage;
	}

	private function getClientCoverImage() {
		return public_asset($this->coverImage);
	}

	private function getSpecificCoverImage() {
		return '/products/' . $this->id . '-cover.webp';
	}

	private function getHasSpecificCoverImage() {
		return file_exists(public_server_path($this->specificCoverImage));
	}

	public function getNewProduct() {
		return NewProduct::find($this->id);
	}

	public function getSpecificImage(int $imageSize = null) {
		return (new LegacyPreview($this, $imageSize))
			->filePath;
	}

	public function getServerSpecificImage(int $imageSize = null) {
		return public_server_path($this->getSpecificImage($imageSize));
	}

	public function getHasImage(int $imageSize = null) {
		return (new LegacyPreview($this, $imageSize))
			->hasImage;
	}

	public function getClientFullImage() {
		return $this->fullImage;
	}

	private function getPriceData() {
		$priceData =
			DB::row('* FROM items_prices WHERE product_id = ?', [$this->id]) ?: (DB::row('* FROM prices_stores WHERE seller_id = ? AND type_id = ?', [$this->seller->id, $this->type->id]) ?:
				DB::row('* FROM prices_default WHERE type_id = ?', [$this->type->id])
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
		return $this->seller_id == auth()->id();
	}

	/*
	* Set methods
	*/
	public function setColorId(int $color_id = null) {
		$this->data['color_id'] = $color_id;

		unset($this->data['color']);

		return $this;
	}

	public function setLogoId(int $logo_id = null) {
		$this->data['logo_id'] = $logo_id;

		unset($this->data['logo']);

		return $this;
	}

	public function setPositionId(int $position_id = null) {
		$this->data['position_id'] = $position_id;

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

		DB::update('products SET colors=? WHERE id = ?', [$availableColorSlugsString, $this->id]);

		$this->data['availableColorSlugs'] = $availableColorSlugs;

		return $this;
	}

	public function updateColorId(int $color_id) {
		$oldColorId = $this->color_id;

		$this->setColorId($color_id);

		if ($this->color_id != $oldColorId) {
			DB::update('products SET color_id = ? WHERE id = ?', [$color_id, $this->id]);
		}

		return $this;
	}

	public function updateStatus(bool $published) {
		$statusId = $published ? 1 : 0;

		DB::update('products SET status=? WHERE id = ?', [$statusId, $this->id]);

		$this->setStatusId($published);

		return $this;
	}

	public function updatePositionIds(mixed $position_ids) {
		if (is_array($position_ids)) {
			$position_ids = implode(',', $position_ids);
		}

		DB::update('products SET positions=? WHERE id = ?', [$position_ids, $this->id]);

		$this->data['position_ids'] = $position_ids;

		unset($this->position_id);

		return $this;
	}

	public function updatePrice(float $newPrice) {
		$priceDifference = $newPrice - $this->fullPrice;
		$newWhiteGain = $priceDifference * 0.75 + $this->whiteGain;
		$newColoredGain = $priceDifference * 0.75 + $this->coloredGain;

		DB::update('items_prices SET price = ?, gain_w = ?, gain_c = ? WHERE product_id = ?', [$newPrice, $newWhiteGain, $newColoredGain, $this->id]);

		return $this;
	}

	/*
	* Instance methods
	*/
	static function new(int $design_id, int $type_id) {
		$design = Design::find($design_id);
		$type = ProductType::find($type_id);
		$productId = NewProduct::where('design_id', $design_id)->where('type_id', $type_id)->first();

		if (!$productId) {
			//get colors
			$colors = [];
			$availableColorSlugs = [];

			foreach ($type->publicColors() as $color) {
				$colors[] = $color;
				$availableColorSlugs[] = $color->tag;
			}

			$color_id = isset($colors[0]) ? $colors[0]->id : 0;
			$availableColorSlugs = implode(',', $availableColorSlugs);

			// Create the item
			$productId = NewProduct::create([
				'name' => $design->name,
				'design_id' => $design->id,
				'user_id' => $design->user_id,
				'type_id' => $type->id,
				'color_id' => $color_id,
				'colors' => $availableColorSlugs,
				'positions' => implode(',', $type->positionList()),
				'status' => 1,
			])->id;
		}

		$product = new self($productId);

		return $product;
	}

	public function delete(bool $permanently = false) {
		if ($permanently || $this->isCustom) {
			DB::delete(self::TABLE . ' WHERE ' . self::INDEX . ' = ?', [$this->id]);
			DB::delete('items_info WHERE product_id = ?', [$this->id]);
			DB::delete('items_previews WHERE product_id = ?', [$this->id]);
			DB::delete('items_prices WHERE product_id = ?', [$this->id]);
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
