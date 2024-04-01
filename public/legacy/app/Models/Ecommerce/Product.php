<?php

namespace App\Models\Ecommerce;

use App\Models\Ecommerce\Product\ProductDescription;
use App\Models\Ecommerce\Product\ProductPrice;
use App\Models\Ecommerce\Product\ProductType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model {

	protected $primaryKey = 'ID';
	protected $table = 'items';
	protected $fillable = ['name', 'desID', 'userID', 'typeID', 'colorID', 'colors', 'status', 'positions'];

	/**
	 * Get the product's type
	 */
	public function type(): HasOne {
		return $this->hasOne(ProductType::class, 'ID', 'typeID');
	}

	/**
	 * Get the product's type
	 */
	public function price(): HasOne {
		return $this->hasOne(ProductPrice::class, 'itemID', 'ID');
	}

	/**
	 * Get the product's description model
	 */
	public function descriptionModel(): HasOne {
		return $this->hasOne(ProductDescription::class, 'itemID', 'ID');
	}

	/**
	 * Get the product's description model
	 */
	public function description(): string|false {
		if ($this->descriptionModel) {
			return $this->descriptionModel->description ?: false;
		}

		return false;
	}

	/**
	 * Get the product's url
	 */
	public function url(): string {
		return '/shop/item?id=' . $this->ID;
	}

	/**
	 * Get the product's url, relative to the client root
	 */
	public function clientUrl(): string {
		return public_client_url($this->url());
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
