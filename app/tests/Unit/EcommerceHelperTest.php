<?php

namespace Tests\Unit;

use App\Helpers\EcommerceHelper;
use Tests\TestCase;

class EcommerceHelperTest extends TestCase
{
    public function test_it_resolves_order_status_tags_to_their_configured_keys(): void
    {
        $this->assertSame(0, EcommerceHelper::statusFromTag('open'));
        $this->assertSame(-1, EcommerceHelper::statusFromTag('cancelled'));
        $this->assertNull(EcommerceHelper::statusFromTag('unknown'));
    }

    public function test_it_resolves_cart_step_tags_to_their_configured_keys(): void
    {
        $this->assertSame(1, EcommerceHelper::stepFromTag('access'));
        $this->assertSame(4, EcommerceHelper::stepFromTag('payment'));
        $this->assertNull(EcommerceHelper::stepFromTag('unknown'));
    }
}
