<?php

namespace Tests\Feature;

use App\Livewire\Portfolio\Product\Images;
use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductType;
use App\Models\User;
use App\Services\Files\ImageProcessor;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class CustomProductEditorTest extends TestCase
{
    use DatabaseTransactions;

    private User $owner;

    private ProductType $type;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->owner = User::factory()->create();
        $this->owner->seller()->create(['name' => 'Custom editor shop', 'sort' => 1, 'status' => true]);
        $this->actingAs($this->owner);
        $this->type = ProductType::where('hidden', 0)->whereHas('colors')->whereHas('positions')->with('colors', 'positions', 'all_positions')->firstOrFail();
    }

    private function product(): Product
    {
        $product = Product::create(['name' => 'Custom test', 'user_id' => $this->owner->id, 'design_id' => 0, 'type_id' => $this->type->id, 'color_id' => $this->type->colors->first()->id, 'positions' => (string) $this->type->positions->first()->id, 'status' => 1]);
        $product->colors()->sync([$this->type->colors->first()->id]);
        $product->specific_price()->create(['price' => 30, 'gain_w' => 7.5, 'gain_c' => 6]);

        return $product;
    }

    private function data(): array
    {
        return ['name' => 'Updated custom', 'type_id' => $this->type->id, 'price' => 35, 'description' => 'Test description', 'colors' => [$this->type->colors->first()->id], 'default-color-id' => $this->type->colors->first()->id];
    }

    public function test_create_and_edit_render_and_escape_the_product_name(): void
    {
        $this->get(route('seller-dashboard.portfolio.products.add'))->assertOk()->assertSee('Aggiungi un prodotto')->assertSee('Colori disponibili');
        $product = $this->product();
        $product->update(['name' => '<script>alert(1)</script>']);
        $this->get(route('seller-dashboard.portfolio.products.show', $product))->assertOk()->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_owner_can_save_colors_details_and_images(): void
    {
        $product = $this->product();
        $data = $this->data();
        $data['cover-image'] = UploadedFile::fake()->image('cover.png');
        $data['more-images'] = [UploadedFile::fake()->image('more.png')];
        $this->post(route('seller-dashboard.portfolio.products.update', $product), $data)->assertSessionHasNoErrors()->assertRedirect(route('seller-dashboard.portfolio.products.show', $product));
        $this->assertSame('Updated custom', $product->fresh()->name);
        $this->assertEquals(35, $product->fresh()->specific_price->price);
        $this->assertEquals(11.25, $product->fresh()->specific_price->gain_w);
        $this->assertDatabaseHas('items_info', ['product_id' => $product->id, 'description' => 'Test description']);
        Storage::disk('public')->assertExists('products/'.$product->id.'-cover.webp');
        $this->assertCount(1, array_filter(Storage::disk('public')->files('products/custom'), fn ($path) => str_contains($path, $product->id.'-more-')));
        $this->get(route('seller-dashboard.portfolio.products.show', $product))->assertOk()->assertSee('Copertina attuale del prodotto');
    }

    public function test_create_moves_only_the_authenticated_sellers_temporary_images(): void
    {
        $response = $this->get(route('seller-dashboard.portfolio.products.add'))->assertOk();
        $tempId = $response->viewData('tempId');
        $filename = $this->type->colors->first()->tag.'-'.$this->type->positions->first()->id.'-400.webp';
        Storage::disk('public')->put('products/temp/'.$tempId.'/'.$filename, 'test image');
        $this->post(route('seller-dashboard.portfolio.products.store'), $this->data() + ['temp_id' => $tempId])->assertSessionHasNoErrors();
        $product = Product::where('user_id', $this->owner->id)->firstOrFail();
        Storage::disk('public')->assertExists('products/custom/'.$product->id.'-'.$filename);
        Storage::disk('public')->assertMissing('products/temp/'.$tempId.'/'.$filename);
        $this->assertSame($this->data()['colors'], $product->colors->modelKeys());
        $this->post(route('seller-dashboard.portfolio.products.store'), $this->data() + ['temp_id' => $tempId])->assertForbidden();
    }

    public function test_other_seller_cannot_read_update_delete_or_upload_to_the_product(): void
    {
        $product = $this->product();
        $other = User::factory()->create();
        $other->seller()->create(['name' => 'Other shop', 'sort' => 1, 'status' => 1]);
        $this->actingAs($other);
        $this->get(route('seller-dashboard.portfolio.products.show', $product))->assertForbidden();
        $this->post(route('seller-dashboard.portfolio.products.update', $product), $this->data())->assertForbidden();
        $this->post(route('seller-dashboard.portfolio.products.delete', $product))->assertForbidden();
        Livewire::test(Images::class, ['productId' => $product->id, 'tempId' => null, 'typeId' => $this->type->id, 'colorIds' => $this->data()['colors']])->assertForbidden();
    }

    public function test_invalid_color_and_description_preserve_product_data(): void
    {
        $product = $this->product();
        $data = $this->data();
        $data['colors'] = [999999];
        $this->post(route('seller-dashboard.portfolio.products.update', $product), $data)->assertSessionHasErrors(['colors', 'default-color-id']);
        $this->assertSame('Custom test', $product->fresh()->name);
        $data = $this->data();
        $data['description'] = str_repeat('x', 501);
        $this->post(route('seller-dashboard.portfolio.products.update', $product), $data)->assertSessionHasErrors('description');
    }

    public function test_validation_redirect_retains_fields_and_temporary_upload_context(): void
    {
        $url = route('seller-dashboard.portfolio.products.add');
        $tempId = $this->get($url)->viewData('tempId');
        $data = $this->data() + ['temp_id' => $tempId];
        $data['price'] = -1;
        $this->from($url)->post(route('seller-dashboard.portfolio.products.store'), $data)->assertSessionHasErrors('price');
        $this->get($url)->assertOk()->assertSee('Updated custom')->assertSee('Test description')->assertViewHas('tempId', $tempId);
    }

    public function test_variant_upload_uses_owned_paths_and_tracks_type_and_color_selection(): void
    {
        $product = $this->product();
        $position = $this->type->all_positions->first(fn ($position) => ! $position->base_of);
        $color = $this->type->colors->first();
        $component = Livewire::test(Images::class, ['productId' => $product->id, 'tempId' => null, 'typeId' => $this->type->id, 'colorIds' => [$color->id]]);
        $component->set('uploads.'.$position->id.'.'.$color->id, UploadedFile::fake()->image('variant.png'))->assertHasNoErrors();
        foreach ([400, 1024] as $size) {
            Storage::disk('public')->assertExists('products/custom/'.$product->id.'-'.$color->tag.'-'.$position->id.'-'.$size.'.webp');
        }
        $component->dispatch('custom-product-variants-updated', typeId: $this->type->id, colorIds: [])->assertSee('Seleziona almeno un colore attivo');
    }

    public function test_missing_types_and_hidden_current_type_are_handled(): void
    {
        $product = $this->product();
        DB::table('product_types')->update(['hidden' => true]);
        $this->get(route('seller-dashboard.portfolio.products.add'))->assertOk()->assertSee('Nessun tipo di prodotto disponibile');
        $this->get(route('seller-dashboard.portfolio.products.show', $product))->assertOk();
        $this->post(route('seller-dashboard.portfolio.products.update', $product), $this->data())->assertSessionHasNoErrors();
    }

    public function test_type_changes_reset_positions_and_use_the_new_types_price_reference(): void
    {
        $product = $this->product();
        $nextType = ProductType::where('hidden', 0)->where('id', '!=', $this->type->id)->whereHas('colors')->whereHas('positions')->with('colors', 'positions')->firstOrFail();
        $seller = $this->owner->seller;
        $seller->prices()->updateOrCreate(['type_id' => $nextType->id], ['price' => 40, 'gain_w' => 7.5, 'gain_c' => 6]);
        $data = $this->data();
        $data['type_id'] = $nextType->id;
        $data['colors'] = [$nextType->colors->first()->id];
        $data['default-color-id'] = $nextType->colors->first()->id;
        $data['price'] = 45;
        $this->post(route('seller-dashboard.portfolio.products.update', $product), $data)->assertSessionHasNoErrors();
        $this->assertEquals($nextType->id, $product->fresh()->type_id);
        $this->assertEquals($nextType->positions->first()->id, $product->fresh()->positions);
        $this->assertEquals(11.25, $product->fresh()->specific_price->gain_w);
        $data['price'] = 0;
        $this->post(route('seller-dashboard.portfolio.products.update', $product), $data)->assertSessionHasNoErrors();
        $this->assertEquals(30, $product->fresh()->specific_price->price);
    }

    public function test_missing_price_records_and_invalid_types_do_not_break_the_editor(): void
    {
        $product = $this->product();
        $product->specific_price()->delete();
        DB::table('prices_sellers')->where('seller_id', $this->owner->seller->id)->delete();
        DB::table('prices_default')->where('type_id', $this->type->id)->delete();
        $this->get(route('seller-dashboard.portfolio.products.show', $product))->assertOk();
        $data = $this->data();
        $data['type_id'] = 999999;
        $url = route('seller-dashboard.portfolio.products.show', $product);
        $this->from($url)->post(route('seller-dashboard.portfolio.products.update', $product), $data)->assertSessionHasErrors('type_id');
        $this->get($url)->assertOk();
    }

    public function test_upload_failure_rolls_back_metadata_changes_and_displays_an_error(): void
    {
        $product = $this->product();
        $this->mock(ImageProcessor::class, function ($mock) {
            $mock->shouldReceive('upload')->once()->andReturn(false);
        });
        $data = $this->data();
        $data['cover-image'] = UploadedFile::fake()->image('cover.png');
        $this->post(route('seller-dashboard.portfolio.products.update', $product), $data)->assertSessionHasErrors('cover-image');
        $this->assertSame('Custom test', $product->fresh()->name);
        $this->assertEquals(30, $product->fresh()->specific_price->price);
    }

    public function test_temporary_uploads_cannot_use_another_sessions_token(): void
    {
        $this->post(route('seller-dashboard.portfolio.products.store'), $this->data() + ['temp_id' => 'temp_unowned'])->assertForbidden();
        Livewire::test(Images::class, ['productId' => null, 'tempId' => 'temp_unowned', 'typeId' => $this->type->id, 'colorIds' => []])->assertForbidden();
        $this->assertSame(0, Product::where('user_id', $this->owner->id)->count());
    }
}
