<?php

namespace App\Models\Ecommerce\Product;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model {

	protected $primaryKey = 'ID';
	protected $table = 'items_types';
	protected $fillable = ['tag', 'name', 'name_it', 'names_it', 'gender', 'sibling', 'unisex', 'sizes', 'positions', 'colors', 'hidden'];

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
		return ProductColor::whereIn('tag', $this->colorList())->get();
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
}
