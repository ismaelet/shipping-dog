<?php

namespace App\Models\Ecommerce\Product;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductType extends Model {

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['tag', 'name', 'name', 'names', 'gender', 'sibling', 'unisex', 'sizes', 'positions', 'category', 'colors', 'hidden'];


	/**
	 * * Relationships
	 */

	/**
	 * Get the positions associated with the product type.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function positions(): BelongsToMany {
		return $this->belongsToMany(ProductPosition::class);
	}

	/**
	 * Get the positions associated with the product type.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function sizes(): BelongsToMany {
		return $this->belongsToMany(ProductSize::class);
	}


	/**
	 * * Attributes
	 */

	/**
	 * The list of the type's colors as array
	 */
	public function colorList(): array {
		return explode(',', $this->colors);
	}

	/**
	 * The list of the type's colors as collection
	 */
	public function colorCollection(): Collection {
		return ProductColor::whereIn('tag', $this->colorList())->orderBy('reorder')->get();
	}

	/**
	 * The list of the type's colors that are publicly available
	 */
	public function publicColors(): Collection {
		return ProductColor::whereIn('tag', $this->colorList())
			->where('hidden', false)
			->orderBy('reorder')
			->orderBy('id')
			->get();
	}

	/**
	 * The list of the type's sizes as array
	 */
	public function sizeList(): array {
		return explode(',', $this->sizes);
	}

	/**
	 * The list of the type's sizes as collection
	 */
	public function sizeCollection(): Collection {
		return ProductSize::whereIn('tag', $this->sizeList())->get();
	}

	/**
	 * Get the type's gender
	 */
	public function gender_model(): BelongsTo {
		return $this->belongsTo(ProductGender::class, 'gender');
	}

	/**
	 * The infos of this type
	 */
	public function info(): HasOne {
		return $this->hasOne(ProductTypeInfo::class, 'product_type_id', 'id');
	}

	/**
	 * Get the type's image
	 */
	public function image(?int $color_id = null, ?int $position_id = null, string $suffix = ''): ?string {
		$color = $color_id ? ProductColor::find($color_id) : $this->colorCollection()->first();
		$position = $position_id ? ProductPosition::find($position_id) : $this->positions->first();

		if (file_exists(public_storage($file = '/product_types/mockups/' . $this->tag . '/' . $color->tag . '-' . $position->basicPositionId() . $suffix . '.webp'))) {
			return $file;
		}

		if (file_exists(public_storage($file = '/product_types/mockups/' . $this->tag . '/' . $color->tag . '-' . $position->basicPositionId() . $suffix . '.png'))) {
			return $file;
		}

		if (file_exists(public_storage($file = '/product_types/mockups/' . $this->tag . '/' . $color->tag . $suffix . '.png'))) {
			return $file;
		}

		return null;
	}

	/**
	 * Get the type's details image
	 */
	public function details(?int $color_id = null, ?int $position_id = null): ?string {
		return $this->image($color_id, $position_id, '-det');
	}
}
