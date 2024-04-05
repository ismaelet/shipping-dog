<?php

namespace App\Models\Ecommerce\Product;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductType extends Model {

	protected $primaryKey = 'ID';
	protected $table = 'items_types';
	protected $fillable = ['tag', 'name', 'name_it', 'names_it', 'gender', 'sibling', 'unisex', 'sizes', 'positions', 'category', 'colors', 'hidden'];

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
		return ProductPosition::whereIn('ID', $this->positionList())->get();
	}

	/**
	 * The infos of this type
	 */
	public function info(): HasOne {
		return $this->hasOne(ProductTypeInfo::class, 'product_type_id', 'ID');
	}

	/**
	 * Get the type's image
	 */
	public function image(?int $colorId = null, ?int $positionId = null): ?string {
		$color = $colorId ? ProductColor::find($colorId) : $this->colorCollection()->first();
		$position = $positionId ? ProductPosition::find($positionId) : $this->positionCollection()->first();

		if (file_exists(public_storage_path($file = '/product_types/mockups/' . $this->tag . '/' . $color->tag . '-' . $position->basicPositionId() . '.webp'))) {
			return $file;
		}

		if (file_exists(public_storage_path($file = '/product_types/mockups/' . $this->tag . '/' . $color->tag . '-' . $position->basicPositionId() . '.png'))) {
			return $file;
		}

		if (file_exists(public_storage_path($file = '/product_types/mockups/' . $this->tag . '/' . $color->tag . '.png'))) {
			return $file;
		}

		return null;
	}
}
