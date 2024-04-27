<?php

namespace App\Models\Ecommerce;

use App\Helpers\EcommerceHelper;
use App\Helpers\PreviewHelper;
use App\Models\Ecommerce\Design\Logo;
use App\Models\Ecommerce\Design\LogoGroup;
use App\Models\Ecommerce\Product\ProductColor;
use App\Models\Ecommerce\Product\ProductDescription;
use App\Models\Ecommerce\Product\ProductPosition;
use App\Models\Ecommerce\Product\ProductPrice;
use App\Models\Ecommerce\Product\ProductType;
use App\Models\Ecommerce\Seller\SellerPrice;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Product extends Model {

	protected $table = 'items';
	protected $fillable = ['name', 'design_id', 'user_id', 'type_id', 'color_id', 'colors', 'status', 'positions'];

	/***************************
	 * * Relationship instances *
	 ***************************/
	/**
	 * Get the product's seller
	 */
	public function seller(): BelongsTo {
		return $this->belongsTo(Seller::class, 'user_id', 'user_id');
	}

	/**
	 * Get the product's design
	 */
	public function design(): BelongsTo {
		return $this->belongsTo(Design::class);
	}

	/**
	 * Get the product's type
	 */
	public function type(): BelongsTo {
		return $this->belongsTo(ProductType::class);
	}

	/**
	 * Get the specific product's price
	 */
	public function specific_price(): HasOne {
		return $this->hasOne(ProductPrice::class);
	}

	/**
	 * Get the default seller's price for the product
	 */
	public function seller_price(): HasOne {
		return $this->hasOne(SellerPrice::class, 'seller_id', 'user_id')->where('type_id', $this->type->id);
	}

	/**
	 * Get the default platform price for the product
	 */
	public function base_price(): HasOne {
		return $this->hasOne(BasePrice::class, 'type_id', 'type_id');
	}
	/************************
	 * * Utility methods *
	 ************************/
	/**
	 * Get the product's logos
	 */
	public function logoGroup(): ?LogoGroup {
		if ($this->design && in_array($this->type_id, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11])) {
			return $this->design->logo_group;
		}

		return null;
	}

	public function logos(): ?Collection {
		if ($this->logoGroup()) {
			return $this->logoGroup()->logos;
		}

		return null;
	}

	public function logoId(int $logoId): void {
		$this->logo_id = $logoId;
	}

	public function logo(): ?Logo {
		if ($this->logo_id) {
			return Logo::find($this->logo_id);
		}

		if ($this->logoGroup() && $this->logoGroup()->forced) {
			return $this->logoGroup()->logos->first();
		}

		return null;
	}

	/**
	 * Get the product's price
	 */
	public function price(): ProductPrice|SellerPrice|BasePrice {
		// Check if the product has a specific price
		if ($this->specific_price) return $this->specific_price;

		// Check if the product has a seller price
		if ($this->seller_price) return $this->seller_price;

		// If not, return the base price
		return $this->base_price;
	}

	public function sellerPrice() {
		return $this->price()->price - $this->price()->gain_w / .75;
	}

	public function productPrice(): float {
		return $this->price()->price ?? 0;
	}

	public function logoPrice(): float {
		return $this->logo() ? $this->logo()->price : 0;
	}

	public function fullPrice(): float {
		return $this->productPrice() + $this->logoPrice();
	}

	public function customerDiscount() {
		return $this->userIsOwner() ? $this->fullPrice() - $this->sellerPrice() : 0;
	}

	public function customerPrice(): float {
		return $this->fullPrice() - $this->customerDiscount();
	}

	public function priceString(): string {
		return EcommerceHelper::formatPrice($this->price()->price);
	}

	public function fullPriceString(): string {
		return EcommerceHelper::formatPrice($this->fullPrice());
	}

	public function sellerPriceString(): string {
		return EcommerceHelper::formatPrice($this->sellerPrice());
	}

	public function customerPriceString(): string {
		return EcommerceHelper::formatPrice($this->customerPrice());
	}

	/**
	 * Get the product gain
	 */
	public function sellerGain(): float {
		return $this->userIsOwner() ? 0 : ($this->color()->type == 'w' ? $this->price()->gain_w : $this->price()->gain_c);
	}

	/**
	 * Get the product's color
	 */
	public function color(): ProductColor {
		return ProductColor::find($this->color_id);
	}

	/**
	 * Get the product's description
	 */
	public function descriptionModel(): HasOne {
		return $this->hasOne(ProductDescription::class, 'product_id', 'id');
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
	public function default_color(): BelongsTo {
		return $this->belongsTo(ProductColor::class, 'color_id');
	}

	/**
	 * Get the product's preview
	 */
	public function preview(?int $imageSize, ?int $color_id, ?int $position_id): PreviewHelper {
		return new PreviewHelper($this, $imageSize, $color_id, $position_id);
	}

	/**
	 * Get the default position
	 */
	public function defaultPositionId(): int {
		return $this->positions ? explode(',', $this->positions)[0] : 0;
	}

	public function defaultPosition(): ?ProductPosition {
		return ProductPosition::find($this->defaultPositionId());
	}

	/**
	 * Get the product's slug
	 */
	public function slug(): string {
		return $this->id . '-' . Str::slug($this->name);
	}

	/**
	 * Get the product's url
	 */
	public function url(): string {
		return '/shop/item?id=' . $this->id;
	}

	public function fullUrl(?int $color_id = null, ?int $position_id = null): string {
		$parameters = ['id' => $this->id];

		if ($color_id) {
			$parameters['color_id'] = $color_id;
		}

		if ($position_id) {
			$parameters['position_id'] = $position_id;
		}

		return '/shop/item?' . http_build_query($parameters);
	}

	/**
	 * Get the base product, without any design
	 */
	public function blankProduct(): ?self {
		return self::where('id', '!=', $this->id)
			->where('type_id', $this->type_id)
			->where('status', 1)
			->where('user_id', 1)
			->oldest()
			->first();
	}

	/**
	 * Get the product's image
	 */
	public function image(?int $imageSize = null, ?int $color_id = null, ?int $position_id = null): string {
		return $this->preview($imageSize, $color_id, $position_id)->image();
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
		return $this->user_id == auth()->id();
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
