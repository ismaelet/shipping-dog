<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model {

	private const SHIPPING_METHODS_WITH_LABELS = [1, 2];

	protected $primaryKey = 'ID';
	protected $table = 'sys_shipping';
	protected $fillable = [];

	/**
	 * Get the shipping methods that require labels
	 */
	public static function shippingMethodsWithLabels(): array {
		return self::SHIPPING_METHODS_WITH_LABELS;
	}
}
