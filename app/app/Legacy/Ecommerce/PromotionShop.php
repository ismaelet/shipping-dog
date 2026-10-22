<?php

namespace App\Legacy\Ecommerce;

use Illuminate\Support\Facades\DB;
use LegacyDB;
use \Legacy\System\Traits\DynamicProperties;
use Legacy\Classes\ViewManager\Ecommerce\PromotionShopTemplate;

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
        $rows = DB::select(
            Promotion::INDEX . ' FROM ' . Promotion::TABLE . ' LIMIT ?',
            [self::LIMIT]
        );

        $promotions = [];

        while ($row = $rows->fetch_assoc()) {
            $promotions[] = new Promotion($row[Promotion::INDEX]);
        }

        return $promotions;
    }

    private function getPromotionCount() {
        return DB::count(Promotion::TABLE);
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
