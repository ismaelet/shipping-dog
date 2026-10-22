<?php

namespace Tests\Unit;

use App\Services\Ecommerce\ProductPriceCalculator;
use PHPUnit\Framework\TestCase;

class ProductPriceCalculatorTest extends TestCase
{
    public function test_it_calculates_base_prices_from_reference_earnings(): void
    {
        $calculator = new ProductPriceCalculator;

        $this->assertSame([
            'base_light_garments' => 32.0,
            'base_dark_garments' => 28.0,
        ], $calculator->bases(40, 6, 9));
    }

    public function test_it_calculates_earnings_for_a_new_price(): void
    {
        $calculator = new ProductPriceCalculator;

        $this->assertSame([
            'price' => 35.0,
            'earnings_for_light_garments' => 2.25,
            'earnings_for_dark_garments' => 5.25,
            'minimum_price' => 28.0,
        ], $calculator->calculate(35, 40, 6, 9));
    }

    public function test_it_clamps_prices_below_the_minimum(): void
    {
        $calculator = new ProductPriceCalculator;

        $this->assertSame([
            'price' => 28.0,
            'earnings_for_light_garments' => 0.0,
            'earnings_for_dark_garments' => 0.0,
            'minimum_price' => 28.0,
        ], $calculator->calculate(20, 40, 6, 9));
    }

    public function test_it_rounds_the_price_and_earnings_to_cents(): void
    {
        $calculator = new ProductPriceCalculator;

        $result = $calculator->calculate(35.127, 40, 6.004, 9.006);

        $this->assertSame(35.13, $result['price']);
        $this->assertSame(2.35, $result['earnings_for_light_garments']);
        $this->assertSame(5.35, $result['earnings_for_dark_garments']);
    }
}
