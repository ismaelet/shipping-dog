<?php

namespace Ecommerce;

use DBOld;
use DynamicProperties;
use ViewManager\Ecommerce\PromotionShopTemplate;

/**
 * PromotionShop
 * 
 * @property int promotionCount
 */
class PromotionShop {

	use DynamicProperties;

	private const LIMIT = 40;
	private const ALLOWED_SORT_TYPES = ['new', 'price'];
	private const DEFAULT_SORT_TYPES = 'new';

	function __construct() {
	}

	private function getPromotions() {
		return rows_to_objects(DBOld::select(Promotion::INDEX . ' FROM ' . Promotion::TABLE . ' LIMIT ?', [self::LIMIT]), Promotion::class);
	}

	private function getPromotionCount() {
		return DBOld::count(Promotion::TABLE);
	}

	private function getSortBy() {
		$sortBy = $_GET['sort'] ?? self::DEFAULT_SORT_TYPES;
		$sortBy = in_array($sortBy, self::ALLOWED_SORT_TYPES) ? $sortBy : self::DEFAULT_SORT_TYPES;

		return $sortBy;
	}

	/**
	 * Render methods
	 */
	public function render() {
		return (new PromotionShopTemplate())->render($this);
	}
}
