<?php

namespace App\Legacy\Ecommerce;

use LegacyDB;
use \Legacy\System\Traits\DynamicProperties;

/**
 * Coupon class
 * 
 * @property bool isExpired
 */
class Coupon {

	use \Legacy\System\Traits\DynamicProperties;

	public const TABLE = 'codes';
	public const INDEX = 'id';

	private $data = [];
	private $timeStart;
	private $timeEnd;

	public $id = null;
	public $code = null;
	public $permanent;
	public $freeShipping;
	public $removesGain;
	public $seller_id;
	public $design_id;
	public $type_id;
	public $color_id;
	public $ourPercentageDiscount;
	public $ourPercentageDiscountTwo;
	public $ourPercentageDiscountMultiple;
	public $sellerPercentageDiscount;
	public $sellerPercentageDiscountTwo;
	public $sellerPercentageDiscountMultiple;
	public $ourFixedDiscount;
	public $ourFixedDiscountMultiple;
	public $sellerFixedDiscount;
	public $sellerFixedDiscountMultiple;
	public $onePerCustomer;
	public $only_one;

	// private $types;
	// private $minPrice;

	function __construct($id) {
		if ($row = DB::row('* FROM codes WHERE id = ?', [$id])) {
			$this->id = $id;
			$this->code = $row['code'];
			$this->timeStart = $row['time_start'];
			$this->timeEnd = $row['time_end'];
			$this->permanent = (bool) $row['permanent'];
			$this->freeShipping = (bool) $row['shipping'];
			$this->removesGain = (bool) $row['zero'];
			$this->seller_id = $row['seller_id'];
			$this->design_id = $row['design_id'];
			$this->type_id = $row['type_id'];
			$this->color_id = $row['color_id'];
			$this->ourPercentageDiscount = $row['mod_us'];
			$this->ourPercentageDiscountTwo = $row['mod_us2'];
			$this->ourPercentageDiscountMultiple = $row['mod_us3'];
			$this->sellerPercentageDiscount = $row['mod_them'];
			$this->sellerPercentageDiscountTwo = $row['mod_them2'];
			$this->sellerPercentageDiscountMultiple = $row['mod_them3'];
			$this->ourFixedDiscount = $row['flat_us'];
			$this->ourFixedDiscountMultiple = $row['flat_us3'];
			$this->sellerFixedDiscount = $row['flat_them'];
			$this->sellerFixedDiscountMultiple = $row['flat_them3'];
			$this->only_one = $row['only_one'];
			$this->onePerCustomer = (bool) $row['one_per_acccount'];

			/*
			$this->minPrice = $row['minPrice'];
			$this->types = !empty($row['types']) ? explode(',', $row['types']) : false;
			*/
		}
	}

	private function getError() {
		$this->isValid;

		return $this->data['error'];
	}

	/**
	 * 
	 */
	public static function generateFromCode(string $code) {
		$code = strtoupper(trim($code));
		$couponId = DB::find('id FROM codes WHERE code=?', [$code]);

		return new self($couponId);
	}
}
