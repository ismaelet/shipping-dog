<?php

namespace App\Models\Ecommerce;

use App\Helpers\EcommerceHelper;
use App\Helpers\PreviewHelper;
use App\Models\Ecommerce\Product\ProductColor;
use App\Models\Ecommerce\Product\ProductDescription;
use App\Models\Ecommerce\Product\ProductPosition;
use App\Models\Ecommerce\Product\ProductPrice;
use App\Models\Ecommerce\Product\ProductType;
use App\Models\Ecommerce\Seller\SellerPrice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Product extends Model {

	protected $primaryKey = 'ID';
	protected $table = 'items';
	protected $fillable = ['name', 'desID', 'userID', 'typeID', 'colorID', 'colors', 'status', 'positions'];

	/**
	 * Get the product's seller
	 */
	public function seller(): BelongsTo {
		return $this->belongsTo(Seller::class, 'userID', 'userID');
	}

	/**
	 * Get the product's design
	 */
	public function design(): BelongsTo {
		return $this->belongsTo(Design::class, 'desID', 'ID');
	}

	/**
	 * Get the product's type
	 */
	public function type(): HasOne {
		return $this->hasOne(ProductType::class, 'ID', 'typeID');
	}

	/**
	 * Get the product's price
	 */
	public function price(): ProductPrice|SellerPrice|BasePrice {
		// Check if the product has a specific price
		if ($this->specific_price()->exists()) return $this->specific_price;

		// Check if the product has a seller price
		if ($this->seller_price()->exists()) return $this->seller_price;

		// If not, return the base price
		return $this->base_price;
	}

	public function specific_price(): HasOne {
		return $this->hasOne(ProductPrice::class, 'itemID', 'ID');
	}

	public function seller_price(): HasOne {
		return $this->hasOne(SellerPrice::class, 'storeID', 'userID')->where('typeID', $this->type->ID);
	}

	public function base_price(): HasOne {
		return $this->hasOne(BasePrice::class, 'typeID', 'typeID');
	}

	public function basePrice() {
		return $this->price()->price - $this->price()->gain_w / .75;
	}

	public function productPrice(): float {
		return $this->price()->price ?? 0;
	}

	public function fullPrice(): float {
		/**
		 * ! Add logo price
		 */
		return $this->productPrice();
	}

	public function customerDiscount() {
		return $this->userIsOwner() ? $this->fullPrice() - $this->basePrice() : 0;
	}

	public function customerPrice(): float {
		return $this->fullPrice() - $this->customerDiscount();
	}

	public function customerPriceString(): string {
		return EcommerceHelper::price($this->customerPrice());
	}

	/**
	 * Get the product's description
	 */
	public function descriptionModel(): HasOne {
		return $this->hasOne(ProductDescription::class, 'itemID', 'ID');
	}

	public function description(): string|false {
		if ($this->descriptionModel) {
			return $this->descriptionModel->description ?: false;
		}

		return false;
	}

	/**
	 * Get the default color
	 */
	public function default_color(): HasOne {
		return $this->hasOne(ProductColor::class, 'ID', 'colorID');
	}

	/**
	 * Get the product's preview
	 */
	public function preview(?int $imageSize, ?int $colorId, ?int $positionId): PreviewHelper {
		return new PreviewHelper($this, $imageSize, $colorId, $positionId);
	}

	/**
	 * Get the default position
	 */
	public function defaultPositionId(): int {
		return $this->positions ? explode(',', $this->positions)[0] : 0;
	}

	public function defaultPosition(): ProductPosition {
		return ProductPosition::find($this->defaultPositionId());
	}

	/**
	 * Get the product's slug
	 */
	public function slug(): string {
		return $this->ID . '-' . Str::slug($this->name);
	}

	/**
	 * Get the product's url
	 */
	public function url(): string {
		return public_client_url('/shop/item?id=' . $this->ID);
	}

	/**
	 * Get the base product, without any design
	 */
	public function blankProduct(): ?self {
		return self::where('ID', '!=', $this->ID)
			->where('typeID', $this->typeID)
			->where('status', 1)
			->where('userID', 1)
			->oldest()
			->first();
	}

	/**
	 * Get the product's image
	 */
	public function image(?int $imageSize = null, ?int $colorId = null, ?int $positionId = null): string {
		return $this->preview($imageSize, $colorId, $positionId)->image();
	}

	/**
	 * Check if the product is a custom product
	 */
	public function isCustom(): bool {
		return $this->design ? $this->design->custom : true;
	}

	/**
	 * Check if the product is owned by the logged user
	 */
	public function userIsOwner(): bool {
		return $this->userID == auth()->id();
	}

	/**
	 * Delete the product and clean the related data
	 */
	public function deleteAndClean(bool $permanently = false): void {
		if ($permanently) {
			$this->price()->delete();
			$this->delete();
		} else {
			$this->status = 0;
			$this->save();
		}
	}
}
