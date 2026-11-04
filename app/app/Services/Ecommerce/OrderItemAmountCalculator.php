<?php

namespace App\Services\Ecommerce;

use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductColor;

final class OrderItemAmountCalculator
{
    public function calculate(
        Product $product,
        ?ProductColor $color,
        int $quantity,
        bool $credit = false,
    ): array {
        return [
            ...$this->calculatePrices(
                product: $product,
                quantity: $quantity,
                credit: $credit,
            ),

            ...$this->calculateSellerEarnings(
                product: $product,
                color: $color,
                quantity: $quantity,
            ),
        ];
    }

    public function calculatePrices(
        Product $product,
        int $quantity,
        bool $credit = false,
    ): array {
        $unitAmountBeforeDiscount = $product->fullPrice();
        $totalAmountBeforeDiscount = $unitAmountBeforeDiscount * $quantity;

        $unitDiscount = $product->customerDiscount();
        $totalDiscount = $unitDiscount * $quantity;

        $unitAmountAfterDiscount = $unitAmountBeforeDiscount - $unitDiscount;
        $totalAmountAfterDiscount = $unitAmountAfterDiscount * $quantity;

        $unitAmountToPay = $credit ? 0 : $unitAmountAfterDiscount;
        $totalAmountToPay = $unitAmountToPay * $quantity;

        return [
            'unit_amount_before_discount' => $unitAmountBeforeDiscount,
            'total_amount_before_discount' => $totalAmountBeforeDiscount,
            'unit_discount' => $unitDiscount,
            'total_discount' => $totalDiscount,
            'unit_amount_after_discount' => $unitAmountAfterDiscount,
            'total_amount_after_discount' => $totalAmountAfterDiscount,
            'unit_amount_to_pay' => $unitAmountToPay,
            'total_amount_to_pay' => $totalAmountToPay,
        ];
    }

    public function calculateSellerEarnings(
        Product $product,
        ?ProductColor $color,
        int $quantity,
    ): array {
        $unitSellerEarningsBeforeReduction = $product->sellerEarningBeforeReduction($color);
        $totalSellerEarningsBeforeReduction = $unitSellerEarningsBeforeReduction * $quantity;

        $unitSellerReduction = $product->userIsOwner()
            ? $unitSellerEarningsBeforeReduction
            : 0;

        $totalSellerReduction = $unitSellerReduction * $quantity;

        $unitSellerEarningsAfterReduction = $unitSellerEarningsBeforeReduction - $unitSellerReduction;
        $totalSellerEarningsAfterReduction = $unitSellerEarningsAfterReduction * $quantity;

        return [
            'unit_seller_earnings_before_reduction' => $unitSellerEarningsBeforeReduction,
            'total_seller_earnings_before_reduction' => $totalSellerEarningsBeforeReduction,
            'unit_seller_reduction' => $unitSellerReduction,
            'total_seller_reduction' => $totalSellerReduction,
            'unit_seller_earnings_after_reduction' => $unitSellerEarningsAfterReduction,
            'total_seller_earnings_after_reduction' => $totalSellerEarningsAfterReduction,
        ];
    }
}
