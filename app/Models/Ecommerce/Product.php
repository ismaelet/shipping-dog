<?php

namespace App\Models\Ecommerce;

use App\Helpers\EcommerceHelper;
use App\Helpers\PreviewHelper;
use App\Models\Ecommerce\Design\Logo;
use App\Models\Ecommerce\Design\LogoGroup;
use App\Models\Ecommerce\Product\ProductColor;
use App\Models\Ecommerce\Product\ProductDescription;
use App\Models\Ecommerce\Product\ProductMockup;
use App\Models\Ecommerce\Product\ProductPosition;
use App\Models\Ecommerce\Product\ProductPrice;
use App\Models\Ecommerce\Product\ProductSize;
use App\Models\Ecommerce\Product\ProductType;
use App\Models\Ecommerce\Seller\SellerPrice;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model {

	use HasFactory, SoftDeletes;

	protected $fillable = ['name', 'design_id', 'user_id', 'type_id', 'color_id', 'colors', 'status', 'positions'];

	/**
	 * * Expand basic methods
	 */
	public function delete() {
		$this->specific_price()->delete();

		return parent::delete();
	}


	/**
	 * * Accessors & Mutators 
	 */
	/**
	 * The position ID attribute mutator.
	 *
	 * @return Attribute The position ID attribute.
	 */
	public function positionId(): Attribute {
		return Attribute::make(
			get: fn (?int $value) => $value ?: (int) explode(',', $this->positions)[0] ?? null,
			set: fn (int $value) => $value,
		);
	}

	/**
	 * * Relationships 
	 */

	/**
	 * Get the product's seller.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function seller(): BelongsTo {
		return $this->belongsTo(Seller::class, 'user_id', 'user_id');
	}

	/**
	 * Get the product's design.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function design(): BelongsTo {
		return $this->belongsTo(Design::class);
	}

	/**
	 * Get the product's type.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function type(): BelongsTo {
		return $this->belongsTo(ProductType::class);
	}

	/**
	 * Get the product's price.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function specific_price(): HasOne {
		return $this->hasOne(ProductPrice::class);
	}

	/**
	 * Get the platform price.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function base_price(): HasOne {
		return $this->hasOne(BasePrice::class, 'type_id', 'type_id');
	}

	/**
	 * Get the position of the product.
	 */
	public function position(): BelongsTo {
		return $this->belongsTo(ProductPosition::class);
	}

	/**
	 * Get the size of the product.
	 */
	public function size(): BelongsTo {
		return $this->belongsTo(ProductSize::class);
	}


	/**
	 * * Attributes 
	 */
	/**
	 * ~ Attributes: Prices 
	 */
	// Get the product's price
	public function price(): ProductPrice|SellerPrice|BasePrice {
		// Check if the product has a specific price
		if ($this->specific_price) {
			return $this->specific_price;
		}

		// Check if the product has a seller price
		if ($priceModel = $this->seller->prices?->where('type_id', $this->type_id)->first()) {
			return $priceModel;
		}

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
		return $this->logo()?->price ?: 0;
	}

	public function positionPrice(): float {
		return $this->position?->price ?: 0;
	}

	public function fullPrice(): float {
		return $this->productPrice() + $this->logoPrice() + $this->positionPrice();
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
		return $this->userIsOwner() ? 0 : ($this->color->type == 'w' ? $this->price()->gain_w : $this->price()->gain_c);
	}

	/**
	 * Get the product's defaul color
	 */
	public function color(): BelongsTo {
		return $this->belongsTo(ProductColor::class);
	}

	/**
	 * Get the product's colors
	 */
	public function colors(): ?Collection {
		return ProductColor::whereIn('tag', explode(',', $this->colors))
			->orderBy('reorder')
			->orderBy('id')
			->get();
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
	 * Get the product's description
	 */
	public function mockup(): HasMany {
		return $this->hasMany(ProductMockup::class);
	}

	/**
	 * Get the product's preview
	 */
	public function previewImage(?int $imageSize = null, ProductColor|int|null $color = null, ProductPosition|int|null $position = null): ?PreviewHelper {
		// Set the product
		$product = $this;

		// Set the color
		if ($color === null) {
			$color = $this->color;
		} else if (is_int($color)) {
			$color = ProductColor::find($color);
		} else if (!is_object($color)) {
			$color = null;
		}

		// Set the position
		if ($position === null) {
			$position = $product->position;
		} else if (is_int($position)) {
			$position = ProductPosition::find($position);
		} else if (!is_object($position)) {
			$position = null;
		}

		// Return null if any of the required data is missing
		if (!$product || !$color || !$position) {
			return null;
		}

		// Return the preview
		return new PreviewHelper(
			product: $product,
			color: $color,
			position: $position,
			imageSize: $imageSize,
		);
	}

	public function positions(): ?Collection {
		return ProductPosition::whereIn('id', explode(',', $this->positions))
			->orderBy('id')
			->get();
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
		return route('products.show', $this);
	}

	public function fullUrl(?int $color_id = null, ?int $position_id = null): string {
		$parameters = ['id' => $this->id];

		if ($color_id) {
			$parameters['color_id'] = $color_id;
		}

		if ($position_id) {
			$parameters['position_id'] = $position_id;
		}

		return env('APP_ENV') == 'local' ? $this->url() . '?' . http_build_query($parameters)
			: '/shop/item?' . http_build_query($parameters);;
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
	public function image(?int $imageSize = null, ProductColor|int|null $color = null, ProductPosition|int|null $position = null): string {
		$preview = $this->previewImage($imageSize, $color, $position);

		return $preview ? $preview->image() : public_asset(PreviewHelper::PLACEHOLDER_IMAGE);
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
	 * ~ Attributes: Logos 
	 */
	/**
	 * Get the product's logo group.
	 *
	 * @return LogoGroup|null The logo group associated with the product, or null if not available.
	 */
	public function logoGroup(): ?LogoGroup {
		if ($this->design && in_array($this->type_id, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11])) {
			return $this->design->logo_group;
		}

		return null;
	}

	/**
	 * Get the product's logos.
	 *
	 * @return \Illuminate\Support\Collection|null
	 */
	public function logos(): ?Collection {
		if ($this->logoGroup()) {
			return $this->logoGroup()->logos;
		}

		return null;
	}

	/**
	 * Set the product's logo id.
	 *
	 * @return \Illuminate\Database\Eloquent\Attributes\Attribute
	 */
	public function logoId(): Attribute {
		return Attribute::make(
			get: fn (?int $value) => $value,
			set: fn (int $value) => $value,
		);
	}

	/**
	 * Get the product's logo.
	 *
	 * @return \App\Models\Ecommerce\Logo|null
	 */
	public function logo(): ?Logo {
		if ($this->logo_id) {
			return Logo::find($this->logo_id);
		}

		if ($this->logoGroup() && $this->logoGroup()->forced) {
			return $this->logoGroup()->logos->first();
		}

		return null;
	}

	// ! Legacy methods
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

	public function coverImage(): ?string {
		switch (true) {
			case file_exists(public_storage($this->specificCoverImage())):
				return $this->specificCoverImage();

			case !$this->isCustom() && file_exists(public_storage($file = '/product_types/covers/' . $this->type->tag . '.webp')):
				return public_asset($file);

			case !$this->isCustom() && file_exists(public_storage($file = '/product_types/covers/' . $this->type->tag . '.jpg')):
				return public_asset($file);

			default:
				return null;
		}
	}

	public function specificCoverImage(): string {
		return public_asset('/products/' . $this->id . '-cover.webp');
	}
}
