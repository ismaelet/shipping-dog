<?php

namespace App\Models\Ecommerce\Product;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model {

	protected $primaryKey = 'ID';
	protected $table = 'items_types';
	protected $fillable = ['tag', 'name', 'name_it', 'names_it', 'gender', 'sibling', 'unisex', 'sizes', 'positions',];

	public function sizeCollection() {
		return ProductSize::whereIn('tag', $this->sizeList())->get();
	}

	public function sizeList() {
		return explode(',', $this->sizes);
	}
}
