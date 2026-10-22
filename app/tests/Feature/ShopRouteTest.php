<?php

namespace Tests\Feature;

use Tests\TestCase;

class ShopRouteTest extends TestCase
{
    public function test_shop_route_renders_the_shop_page_and_product_list(): void
    {
        $this->get(route('shop'))
            ->assertOk()
            ->assertViewIs('ecommerce.shop')
            ->assertSee('Trova il tuo prossimo preferito.')
            ->assertSee('La collezione')
            ->assertSee('Tutti i prodotti')
            ->assertSee('Ordina:');
    }
}
