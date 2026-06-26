<?php

namespace App\Services\Ecommerce;

final class ProductPriceCalculator {

    /**
     * Constant: The percentage of increment in price that goes to the seller earnings
     * 
     * It is not 100% to cover VAT and PayPal fees related to the increment
     */
    public const SELLER_PERCENTAGE = 0.75;

    /**
     * Calculate the immutable base values represented by a price record
     */
    public function bases(
        float $price,
        float $earningsForLightGarments,
        float $earningsForDarkGarments,
    ): array {
        return [
            'base_light_garments' => $price - ($earningsForLightGarments / self::SELLER_PERCENTAGE),
            'base_dark_garments' => $price - ($earningsForDarkGarments / self::SELLER_PERCENTAGE),
        ];
    }

    /**
     * Calculate the earnings for a new selling price
     */
    public function calculate(
        float $newPrice,
        float $referencePrice,
        float $referenceEarningsForLightGarments,
        float $referenceEarningsForDarkGarments,
    ): array {
        $bases = $this->bases(
            price: $referencePrice,
            earningsForLightGarments: $referenceEarningsForLightGarments,
            earningsForDarkGarments: $referenceEarningsForDarkGarments,
        );

        $minimumPrice = $this->minimumPrice(
            price: $referencePrice,
            earningsForLightGarments: $referenceEarningsForLightGarments,
            earningsForDarkGarments: $referenceEarningsForDarkGarments,
        );

        $newPrice = max($newPrice, $minimumPrice);

        return [
            'price' => round($newPrice, 2),

            'earnings_for_light_garments' => round(max(
                0,
                ($newPrice - $bases['base_light_garments']) * self::SELLER_PERCENTAGE
            ), 2),

            'earnings_for_dark_garments' => round(max(
                0,
                ($newPrice - $bases['base_dark_garments']) * self::SELLER_PERCENTAGE
            ), 2),

            'minimum_price' => $minimumPrice,
        ];
    }

    /**
     * Preserve the same minimum-price formula used by the pricing page.
     */
    public function minimumPrice(
        float $price,
        float $earningsForLightGarments,
        float $earningsForDarkGarments,
    ): float {
        return round(
            $price - (max($earningsForLightGarments, $earningsForDarkGarments) / self::SELLER_PERCENTAGE),
            2,
        );
    }
}
