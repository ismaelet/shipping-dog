<?php

namespace Tests\Feature;

use App\Livewire\Ecommerce\Seller\Collection\ProductList;
use App\Models\Ecommerce\Design\DesignCollection;
use App\Models\Ecommerce\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class SellerCollectionTest extends TestCase
{
    use DatabaseTransactions;

    private User $owner;

    private int $sellerId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::factory()->create();
        $this->sellerId = $this->createSeller($this->owner->id, 'Owner shop');
    }

    private function createSeller(int $userId, string $name, bool $active = true): int
    {
        return DB::table('sellers')->insertGetId([
            'user_id' => $userId, 'name' => $name, 'status' => $active, 'hidden' => false, 'sales' => 0,
        ]);
    }

    private function collection(?int $userId = null, string $name = 'Animali'): int
    {
        return DB::table('collections')->insertGetId([
            'user_id' => $userId ?? $this->owner->id, 'name' => $name, 'status' => 2,
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    private function design(int $collection, ?int $userId = null, string $name = 'Design'): int
    {
        return DB::table('designs')->insertGetId([
            'name' => $name, 'user_id' => $userId ?? $this->owner->id, 'collection' => $collection,
            'status' => true, 'hidden' => false, 'sales' => 0, 'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    private function product(int $design, string $name, float $price, int $sales = 0, ?string $createdAt = null): int
    {
        $id = DB::table('products')->insertGetId([
            'name' => $name, 'design_id' => $design, 'user_id' => $this->owner->id,
            'type_id' => DB::table('product_types')->value('id'), 'color_id' => DB::table('product_colors')->value('id'),
            'status' => true, 'sales' => $sales, 'created_at' => $createdAt ?? now(), 'updated_at' => now(),
        ]);
        DB::table('items_prices')->insert(['product_id' => $id, 'price' => $price, 'gain_w' => 1, 'gain_c' => 1]);

        return $id;
    }

    private function url(int $collection, string $suffix = '', ?int $sellerId = null): string
    {
        return '/seller/'.($sellerId ?? $this->sellerId).'/collection/'.$collection.$suffix;
    }

    public function test_visitors_see_the_collection_without_management_controls(): void
    {
        $collection = $this->collection();
        $this->product($this->design($collection), 'Magliettona', 20);

        $this->get($this->url($collection))
            ->assertOk()
            ->assertSee('Magliettona')
            ->assertSee('1 grafica')
            ->assertDontSee('Gestisci grafiche')
            ->assertDontSee('Elimina collezione');
    }

    public function test_owner_sees_management_controls(): void
    {
        $collection = $this->collection();

        $this->actingAs($this->owner)->get($this->url($collection))
            ->assertOk()
            ->assertSee('Gestisci grafiche')
            ->assertSee('La tua collezione è ancora vuota');
    }

    public function test_owner_picker_lists_designs_and_flags_the_ones_that_will_move(): void
    {
        $collection = $this->collection(null, 'Current');
        $other = $this->collection(null, 'Altra');
        $this->design($collection, null, 'In current');
        $this->design($other, null, 'Da spostare');

        $this->actingAs($this->owner)->get($this->url($collection))
            ->assertOk()
            ->assertSee('Da spostare')
            ->assertSee('In «Altra»', false)
            ->assertSee('Grafiche della collezione')
            ->assertSee('Salva copertina')
            ->assertSee('Eliminare «Current»?', false);
    }

    public function test_empty_collection_message_for_visitors(): void
    {
        $this->get($this->url($this->collection()))->assertOk()->assertSee('Questa collezione non contiene ancora prodotti.');
    }

    public function test_inactive_seller_collection_is_hidden_from_visitors_but_not_owner(): void
    {
        DB::table('sellers')->where('id', $this->sellerId)->update(['status' => false]);
        $collection = $this->collection();

        $this->get($this->url($collection))->assertRedirect(route('shop'));
        $this->actingAs($this->owner)->get($this->url($collection))->assertOk();
    }

    public function test_collection_of_another_seller_redirects(): void
    {
        $other = User::factory()->create();
        $otherSeller = $this->createSeller($other->id, 'Other');

        $this->get($this->url($this->collection(), '', $otherSeller))->assertRedirect(route('shop'));
    }

    public function test_products_can_be_sorted(): void
    {
        $collection = $this->collection();
        $design = $this->design($collection);

        // Create products with different prices, sales counts, and creation dates.
        $this->product($design, 'Cheap', 10, 1, '2020-01-01');
        $this->product($design, 'Pricey', 50, 5, '2021-01-01');
        $this->product($design, 'Newest', 30, 3, '2022-01-01');

        $component = Livewire::test(ProductList::class, [
            'seller' => Seller::find($this->sellerId),
            'collection' => DesignCollection::find($collection),
        ]);

        // Extract product names in their rendered order for easier assertions.
        $order = fn () => $component->viewData('products')
            ->pluck('name')
            ->all();

        // Products should be sorted by popularity (sales) by default.
        $this->assertSame(['Pricey', 'Newest', 'Cheap'], $order());

        $component->set('sort', 'recent');
        $this->assertSame(['Newest', 'Pricey', 'Cheap'], $order());

        $component->set('sort', 'price_asc');
        $this->assertSame(['Cheap', 'Newest', 'Pricey'], $order());

        $component->set('sort', 'price_desc');
        $this->assertSame(['Pricey', 'Newest', 'Cheap'], $order());

        // Unsupported sort values should fall back to the default.
        $component->set('sort', 'invalid')
            ->assertSet('sort', 'popular');

        $this->assertSame(['Pricey', 'Newest', 'Cheap'], $order());
    }

    public function test_products_can_be_paginated_and_sorting_resets_the_page(): void
    {
        $productsPerPage = ProductList::PER_PAGE;
        $collection = $this->collection();
        $design = $this->design($collection);

        // Create enough products to fill the first page and leave three on the second.
        $totalProducts = $productsPerPage + 3;

        for ($i = 1; $i <= $totalProducts; $i++) {
            $this->product($design, "Product $i", $i * 10);
        }

        $component = Livewire::test(ProductList::class, [
            'seller' => Seller::find($this->sellerId),
            'collection' => DesignCollection::find($collection),
        ]);

        $component->set('sort', 'price_asc');

        // The first page should contain exactly PER_PAGE products.
        $this->assertCount(
            $productsPerPage,
            $component->viewData('products')
        );

        // Moving to the next page should show only the three remaining products.
        $component->call('nextPage')
            ->assertSet('paginators.page', 2);

        $this->assertSame(
            [
                'Product '.($productsPerPage + 1),
                'Product '.($productsPerPage + 2),
                'Product '.($productsPerPage + 3),
            ],
            $component->viewData('products')->pluck('name')->all()
        );

        // Changing the sort order should automatically return to the first page.
        $component->set('sort', 'price_desc')
            ->assertSet('paginators.page', 1);

        // The first product should now be the most expensive one.
        $this->assertSame(
            "Product $totalProducts",
            $component->viewData('products')->first()->name
        );

        $this->assertCount(
            $productsPerPage,
            $component->viewData('products')
        );
    }

    public function test_sort_is_read_from_the_url(): void
    {
        $collection = $this->collection();
        $design = $this->design($collection);
        $this->product($design, 'Cheap', 10);
        $this->product($design, 'Pricey', 50);

        $products = $this->get($this->url($collection).'?sort=price_desc')->assertOk()->assertSee('Pricey');
        $products->assertSeeInOrder(['Pricey', 'Cheap']);
    }

    public function test_sync_adds_moves_and_removes_designs_without_deleting_them(): void
    {
        $target = $this->collection(null, 'Target');
        $source = $this->collection(null, 'Source');
        $moved = $this->design($source);
        $removed = $this->design($target);

        $this->actingAs($this->owner)
            ->put($this->url($target, '/designs'), ['designs' => [$moved]])
            ->assertRedirect();

        $this->assertSame($target, (int) DB::table('designs')->where('id', $moved)->value('collection'));
        $this->assertSame(0, (int) DB::table('collections')->where('id', $source)->value('status'));

        $row = DB::table('designs')->where('id', $removed)->first();
        $this->assertNull($row->deleted_at);
        $this->assertSame(0, (int) $row->collection);

        $this->actingAs($this->owner)->put($this->url($target, '/designs'))->assertRedirect();
        $this->assertSame(0, (int) DB::table('designs')->where('id', $moved)->value('collection'));
        $this->assertSame(0, (int) DB::table('collections')->where('id', $target)->value('status'));
    }

    public function test_designs_of_other_sellers_are_rejected(): void
    {
        $other = User::factory()->create();
        $this->createSeller($other->id, 'Other');
        $foreign = $this->design(0, $other->id);
        $collection = $this->collection();

        $this->actingAs($this->owner)
            ->put($this->url($collection, '/designs'), ['designs' => [$foreign]])
            ->assertSessionHasErrors('designs.0');

        $this->assertSame(0, (int) DB::table('designs')->where('id', $foreign)->value('collection'));
    }

    public function test_only_the_owner_can_manage_the_collection(): void
    {
        $collection = $this->collection();
        $design = $this->design(0);
        $intruder = User::factory()->create();

        $this->put($this->url($collection, '/designs'), ['designs' => [$design]])->assertForbidden();
        $this->actingAs($intruder)->put($this->url($collection, '/designs'), ['designs' => [$design]])->assertForbidden();
        $this->actingAs($intruder)->delete($this->url($collection))->assertForbidden();
    }

    public function test_deleting_a_collection_keeps_designs_and_products(): void
    {
        $collection = $this->collection();
        $design = $this->design($collection);
        $product = $this->product($design, 'Keep me', 10);

        $this->actingAs($this->owner)->delete($this->url($collection))->assertRedirect();

        $this->assertDatabaseMissing('collections', ['id' => $collection]);
        $this->assertSame(0, (int) DB::table('designs')->where('id', $design)->value('collection'));
        $this->assertDatabaseHas('products', ['id' => $product, 'deleted_at' => null]);
    }

    public function test_cover_upload_is_resized_without_upscaling_and_stored_as_webp(): void
    {
        Storage::fake('public');
        $collection = $this->collection();

        $this->actingAs($this->owner)
            ->post($this->url($collection, '/cover'), ['image' => UploadedFile::fake()->image('big.png', 3000, 1000)])
            ->assertSessionHasNoErrors();

        $this->assertSame(2560, getimagesizefromstring(Storage::disk('public')->get("shops/collections/$collection.webp"))[0]);
        Storage::disk('public')->assertExists("shops/collections/{$collection}_m.webp");

        $this->actingAs($this->owner)
            ->post($this->url($collection, '/cover'), ['image' => UploadedFile::fake()->image('small.jpg', 300, 200)]);
        $this->assertSame(300, getimagesizefromstring(Storage::disk('public')->get("shops/collections/$collection.webp"))[0]);
    }

    public function test_cover_upload_validates_the_file(): void
    {
        Storage::fake('public');
        $collection = $this->collection();

        $this->actingAs($this->owner)
            ->post($this->url($collection, '/cover'), ['image' => UploadedFile::fake()->create('doc.pdf', 10, 'application/pdf')])
            ->assertSessionHasErrors('image');
        $this->actingAs($this->owner)
            ->post($this->url($collection, '/cover'), ['image' => UploadedFile::fake()->image('anim.gif')])
            ->assertSessionHasErrors('image');

        Storage::disk('public')->assertMissing("shops/collections/$collection.webp");
    }
}
