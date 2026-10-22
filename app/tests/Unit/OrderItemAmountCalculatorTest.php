<?php

namespace Tests\Unit;

use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductColor;
use App\Services\Ecommerce\OrderItemAmountCalculator;
use Mockery;
use PHPUnit\Framework\TestCase;

class OrderItemAmountCalculatorTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_it_calculates_amounts_and_seller_earnings_for_a_customer(): void
    {
        $product = Mockery::mock(Product::class);
        $color = Mockery::mock(ProductColor::class);

        $product->shouldReceive('fullPrice')->once()->andReturn(100.0);
        $product->shouldReceive('customerDiscount')->once()->andReturn(15.0);
        $product->shouldReceive('sellerEarningBeforeReduction')->with($color)->once()->andReturn(8.0);
        $product->shouldReceive('userIsOwner')->once()->andReturn(false);

        $result = (new OrderItemAmountCalculator)->calculate($product, $color, 2);

        $this->assertSame([
            'unit_amount_before_discount' => 100.0,
            'total_amount_before_discount' => 200.0,
            'unit_discount' => 15.0,
            'total_discount' => 30.0,
            'unit_amount_after_discount' => 85.0,
            'total_amount_after_discount' => 170.0,
            'unit_amount_to_pay' => 85.0,
            'total_amount_to_pay' => 170.0,
            'unit_seller_earnings_before_reduction' => 8.0,
            'total_seller_earnings_before_reduction' => 16.0,
            'unit_seller_reduction' => 0,
            'total_seller_reduction' => 0,
            'unit_seller_earnings_after_reduction' => 8.0,
            'total_seller_earnings_after_reduction' => 16.0,
        ], $result);
    }

    public function test_it_applies_credit_and_reduces_owner_earnings(): void
    {
        $product = Mockery::mock(Product::class);
        $color = Mockery::mock(ProductColor::class);

        $product->shouldReceive('fullPrice')->once()->andReturn(50.0);
        $product->shouldReceive('customerDiscount')->once()->andReturn(10.0);
        $product->shouldReceive('sellerEarningBeforeReduction')->with($color)->once()->andReturn(7.0);
        $product->shouldReceive('userIsOwner')->once()->andReturn(true);

        $result = (new OrderItemAmountCalculator)->calculate($product, $color, 3, credit: true);

        $this->assertSame(0, $result['unit_amount_to_pay']);
        $this->assertSame(0, $result['total_amount_to_pay']);
        $this->assertSame(7.0, $result['unit_seller_reduction']);
        $this->assertSame(21.0, $result['total_seller_reduction']);
        $this->assertSame(0.0, $result['total_seller_earnings_after_reduction']);
    }
}
