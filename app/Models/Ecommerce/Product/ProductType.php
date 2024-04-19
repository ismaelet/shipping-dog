<?php

namespace App\Models\Ecommerce\Product;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class ProductType
 * 
 * @method string image(?int $color_id = null, ?int $position_id = null)
 */
class ProductType extends Model {


	protected $table = 'items_types';
	protected $fillable = ['tag', 'name', 'name', 'names', 'gender', 'sibling', 'unisex', 'sizes', 'positions', 'category', 'colors', 'hidden'];

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
	 * The list of the type's positions as array
	 */
	public function positionList(): array {
		return explode(',', $this->positions);
	}

	/**
	 * The list of the type's positions as collection
	 */
	public function positionCollection(): Collection {
		return ProductPosition::whereIn('id', $this->positionList())->get();
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
		$position = $position_id ? ProductPosition::find($position_id) : $this->positionCollection()->first();

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
