<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SearchPageTest extends TestCase {
    use DatabaseTransactions;

    private const TERM = 'zqxsrch';

    private function seller(string $name, bool $active = true): int {
        $userId = User::factory()->create()->id;
        DB::table('sellers')->insert(['user_id' => $userId, 'name' => $name, 'status' => $active, 'hidden' => false, 'sales' => 0]);

        return $userId;
    }

    private function design(int $userId, string $name, bool $withProduct = true, int $sales = 0, ?string $createdAt = null): int {
        $id = DB::table('designs')->insertGetId([
            'name' => $name, 'user_id' => $userId, 'status' => true, 'hidden' => false, 'sales' => $sales,
            'created_at' => $createdAt ?? now(), 'updated_at' => now(),
        ]);

        if ($withProduct) {
            $productId = DB::table('products')->insertGetId([
                'name' => $name, 'design_id' => $id, 'user_id' => $userId, 'type_id' => DB::table('product_types')->value('id'),
                'color_id' => DB::table('product_colors')->value('id'), 'status' => true,
            ]);
            $this->assertNotNull($productId);
        }

        return $id;
    }

    public function test_empty_search_prompts_without_no_results_message(): void {
        $this->get('/search')->assertOk()->assertDontSee('Nessun risultato');
    }

    public function test_short_query_explains_minimum_length(): void {
        $this->get('/search?search=ab')->assertOk()->assertSee('almeno 3 caratteri')->assertDontSee('Nessun risultato');
    }

    public function test_search_term_is_escaped(): void {
        $this->get('/search?search=' . urlencode('<script>x</script>'))->assertOk()->assertDontSee('<script>x</script>', false);
    }

    public function test_empty_results(): void {
        $this->get('/search?search=' . self::TERM . 'none')->assertOk()->assertSee('Nessun risultato per');
    }

    public function test_finds_designs_by_name_and_by_seller_name(): void {
        $seller = $this->seller('Shop ' . self::TERM);
        $this->design($seller, 'Gatto ' . self::TERM);
        $other = $this->seller('Altro');
        $this->design($other, 'Cane ' . self::TERM);

        $this->get('/search?type=designs&search=' . self::TERM)
            ->assertOk()->assertSee('Gatto ' . self::TERM)->assertSee('Cane ' . self::TERM);

        $this->get('/search?search=Shop+' . self::TERM)
            ->assertOk()->assertSee('1 design e 1 negozio');
    }

    public function test_excludes_inactive_sellers_and_designs_without_products(): void {
        $inactive = $this->seller('Inactive ' . self::TERM, false);
        $this->design($inactive, 'Hidden ' . self::TERM);
        $active = $this->seller('Active');
        $this->design($active, 'Noproduct ' . self::TERM, false);

        $this->get('/search?search=' . self::TERM)
            ->assertOk()->assertSee('Nessun risultato')->assertDontSee('Hidden ' . self::TERM);
    }

    public function test_type_filter(): void {
        $seller = $this->seller('Shop ' . self::TERM);
        $this->design($seller, 'Design ' . self::TERM);

        $this->get('/search?type=sellers&search=' . self::TERM)
            ->assertOk()->assertSee('Shop ' . self::TERM)->assertDontSee('Design ' . self::TERM);
        $this->get('/search?type=designs&search=' . self::TERM)
            ->assertOk()->assertSee('Design ' . self::TERM);
    }

    public function test_relevance_and_sorting(): void {
        $seller = $this->seller('Seller');
        $this->design($seller, 'Big ' . self::TERM, true, 100, '2020-01-01');
        $this->design($seller, self::TERM . ' prefix', true, 1, '2021-01-01');
        $this->design($seller, self::TERM, true, 0, '2022-01-01');

        $order = fn (string $query) => $this->get('/search?type=designs&search=' . self::TERM . $query)
            ->assertOk()->viewData('designs')->pluck('name')->all();

        $this->assertSame([self::TERM, self::TERM . ' prefix', 'Big ' . self::TERM], $order(''));
        $this->assertSame(['Big ' . self::TERM, self::TERM . ' prefix', self::TERM], $order('&sort=popular'));
        $this->assertSame([self::TERM, self::TERM . ' prefix', 'Big ' . self::TERM], $order('&sort=recent'));
    }

    public function test_pagination_preserves_query_parameters(): void {
        $seller = $this->seller('Seller');
        foreach (range(1, 21) as $i) {
            $this->design($seller, "Item $i " . self::TERM);
        }

        $response = $this->get('/search?type=designs&sort=popular&search=' . self::TERM)->assertOk();
        $this->assertCount(20, $response->viewData('designs'));
        $this->assertStringContainsString('sort=popular', $response->viewData('designs')->nextPageUrl());
        $this->assertStringContainsString('type=designs', $response->viewData('designs')->nextPageUrl());
        $this->assertCount(1, $this->get('/search?type=designs&page=2&search=' . self::TERM)->viewData('designs'));
    }
}
