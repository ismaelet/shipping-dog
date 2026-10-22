<?php

namespace Tests\Feature;

use App\Livewire\Shop\ProductList;
use App\Models\Ecommerce\Product\ProductType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class ShopProductListTest extends TestCase {
    use DatabaseTransactions;

    public function test_it_renders_an_empty_state_when_search_has_no_matches(): void {
        Livewire::withQueryParams(['search' => 'no-shop-product-matches-this-query'])
            ->test(ProductList::class)
            ->assertSee('Nessun articolo trovato')
            ->assertSee('Rimuovi i filtri');
    }

    public function test_it_accepts_supported_sort_orders_and_ignores_unsupported_values(): void {
        Livewire::withQueryParams(['search' => 'no-shop-product-matches-this-query'])
            ->test(ProductList::class)
            ->assertSet('sort', 'popular')
            ->call('changeSort', 'new')
            ->assertSet('sort', 'new')
            ->call('changeSort', 'invalid')
            ->assertSet('sort', 'new');
    }

    public function test_type_filter_resolves_its_gender_and_toggling_it_clears_the_filter(): void {
        $genderId = DB::table('items_genders')->insertGetId([
            'tag' => 'TST',
            'name' => 'Test',
        ]);

        $typeId = DB::table('product_types')->insertGetId([
            'tag' => 'test-shop-type',
            'name' => 'Test shop type',
            'names' => 'Test shop type',
            'gender_id' => $genderId,
            'category' => 'c',
            'hidden' => false,
        ]);

        $this->assertSame($genderId, ProductType::findOrFail($typeId)->gender->id);

        Livewire::withQueryParams(['search' => 'no-shop-product-matches-this-query'])
            ->test(ProductList::class)
            ->call('selectType', $typeId, $genderId)
            ->assertSet('selectedTypeId', $typeId)
            ->assertSet('selectedGenderId', $genderId)
            ->call('selectType', $typeId, $genderId)
            ->assertSet('selectedTypeId', null)
            ->assertSet('selectedGenderId', null);
    }

    public function test_clear_filters_resets_search_type_and_gender(): void {
        Livewire::withQueryParams([
            'search' => 'no-shop-product-matches-this-query',
            'type' => 999999,
            'gender' => 999999,
        ])
            ->test(ProductList::class)
            ->call('removeFilters')
            ->assertSet('search', '')
            ->assertSet('selectedTypeId', null)
            ->assertSet('selectedGenderId', null);
    }
}
