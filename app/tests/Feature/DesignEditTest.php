<?php

namespace Tests\Feature;

use App\Actions\Designs\EditDesign;
use App\Livewire\Ecommerce\Designs\Edit\BackgroundColor;
use App\Livewire\Ecommerce\Designs\Edit\DefaultColor;
use App\Livewire\Ecommerce\Designs\Edit\Name;
use App\Livewire\Ecommerce\Designs\Edit\ProductColors;
use App\Livewire\Ecommerce\Designs\Edit\ProductList;
use App\Livewire\Ecommerce\Designs\Edit\ProductPosition;
use App\Livewire\Ecommerce\Designs\Edit\Tags;
use App\Models\Ecommerce\Design;
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

class DesignEditTest extends TestCase
{
    use DatabaseTransactions;

    private function design(): Design
    {
        $owner = User::factory()->create();
        $this->actingAs($owner);

        return Design::create(['name' => 'Test design', 'user_id' => $owner->id, 'status' => 1, 'color' => '#123456', 'ratio' => 1]);
    }

    public function test_page_is_read_only_and_editors_save_metadata(): void
    {
        $design = $this->design();
        $count = Product::where('design_id', $design->id)->count();
        $this->get(route('designs.edit', $design))->assertOk()->assertSee('Crea prodotto');
        $this->get(route('designs.edit', $design))->assertOk();
        $this->assertSame($count, Product::where('design_id', $design->id)->count());
        Livewire::test(Name::class, ['design' => $design])->set('value', 'Nuovo nome')->call('save')->assertHasNoErrors()->assertSet('saveStatus', 'saved');
        $this->assertSame('Nuovo nome', $design->fresh()->name);
        Livewire::test(Name::class, ['design' => $design])->set('value', '')->call('save')->assertHasErrors('value');
    }

    public function test_name_confirmation_only_appears_after_a_changed_name_is_saved(): void
    {
        $design = $this->design();
        $editor = Livewire::test(Name::class, ['design' => $design]);

        $editor->call('save')->assertSet('saveStatus', 'inactive');
        $editor->set('value', 'Nome modificato')->call('save')->assertSet('saveStatus', 'saved');
        $this->assertSame('Nome modificato', $design->fresh()->name);
        $editor->call('save')->assertSet('saveStatus', 'inactive');
        $editor->set('value', 'Modifica annullata')->set('value', 'Nome modificato')
            ->call('save')->assertSet('saveStatus', 'inactive');
    }

    public function test_non_owner_cannot_view_or_mutate_design(): void
    {
        $design = $this->design();
        $this->actingAs(User::factory()->create());
        $this->get(route('designs.edit', $design))->assertForbidden();
        $this->post(route('designs.update-tags', $design), ['tags' => 'changed'])->assertForbidden();
        $this->delete(route('designs.destroy', $design))->assertForbidden();
        Livewire::test(Name::class, ['design' => $design])->assertForbidden();
    }

    public function test_tags_are_normalized_and_repeated_saves_do_not_increment_counts(): void
    {
        $design = $this->design();
        $tag = 'testtag'.$design->id;
        $editor = Livewire::test(Tags::class, ['design' => $design]);
        $editor->set('value', '#'.strtoupper($tag).', '.$tag)->call('save')->assertHasNoErrors();
        $editor->call('save')->assertHasNoErrors();
        $this->assertDatabaseHas('sys_tags', ['tag' => $tag, 'count' => 1]);
        $editor->set('value', '')->call('save');
        $this->assertDatabaseMissing('sys_tags', ['tag' => $tag]);
    }

    public function test_background_validation_and_preview_invalidation(): void
    {
        Storage::fake('public');
        $design = $this->design();
        $types = ProductType::where('hidden', 0)->whereHas('colors')->whereHas('positions')->limit(2)->get();
        $this->assertCount(2, $types);
        $previewPaths = [];
        foreach ($types as $type) {
            $product = EditDesign::createProduct($design, $type);
            $path = 'products/'.$type->tag.'/'.$product->id.'-cached.webp';
            Storage::disk('public')->put($path, 'cached');
            $previewPaths[] = $path;
        }
        Storage::disk('public')->put('designs/previews/'.$design->id.'.webp', 'cached');
        $editor = Livewire::test(BackgroundColor::class, ['design' => $design]);
        $editor->set('value', 'invalid')->call('save')->assertHasErrors('value');
        $editor->set('value', '#abcdef')->call('save')->assertHasNoErrors()->assertSet('saveStatus', 'saved');
        Storage::disk('public')->assertMissing('designs/previews/'.$design->id.'.webp');
        foreach ($previewPaths as $path) {
            Storage::disk('public')->assertMissing($path);
        }
        $this->assertSame('#abcdef', $design->fresh()->color);
    }

    public function test_product_creation_is_explicit_and_idempotent_and_status_is_editable(): void
    {
        $design = $this->design();
        $type = ProductType::where('hidden', 0)->whereHas('colors')->whereHas('positions')->firstOrFail();
        $editor = Livewire::test(ProductList::class, ['design' => $design]);
        $editor->call('createProduct', $type->id)->assertHasNoErrors();
        $editor->call('createProduct', $type->id)->assertHasNoErrors();
        $this->assertSame(1, Product::where('design_id', $design->id)->where('type_id', $type->id)->count());
        $product = Product::where('design_id', $design->id)->firstOrFail();
        $this->assertSame($type->colors->count(), $product->colors->count());
        $status = (bool) $product->status;
        $editor->call('toggleStatus', $product->id)->assertHasNoErrors();
        $this->assertSame(! $status, (bool) $product->fresh()->status);
        $editor->call('toggleStatus', $product->id)->call('editProduct', $product->id)->assertHasNoErrors();
    }

    public function test_color_selection_cannot_be_empty_or_foreign(): void
    {
        $design = $this->design();
        $type = ProductType::where('hidden', 0)->whereHas('colors')->whereHas('positions')->firstOrFail();
        $product = EditDesign::createProduct($design, $type);
        Livewire::test(ProductColors::class, ['product' => $product, 'positionId' => $product->position_id])->set('activeColorIds', [])->call('save')->assertHasErrors('activeColorIds');
        Livewire::test(ProductColors::class, ['product' => $product, 'positionId' => $product->position_id])->set('activeColorIds', [999999])->call('save')->assertHasErrors('activeColorIds.0');
    }

    public function test_mockups_update_existing_record_and_last_position_cannot_be_disabled(): void
    {
        $design = $this->design();
        $type = ProductType::where('hidden', 0)->whereHas('colors')->whereHas('positions', fn ($q) => $q->where('editable', true))->firstOrFail();
        $product = EditDesign::createProduct($design, $type);
        $position = $type->positions->firstWhere('editable', true);
        $product->update(['positions' => (string) $position->id]);
        $editor = Livewire::test(ProductPosition::class, ['product' => $product, 'positionId' => $position->id]);
        $editor->call('toggle')->assertHasErrors('position');
        if (! DB::table('items_mockups')->where('type_id', $type->id)->where('position_id', $position->id)->exists()) {
            DB::table('items_mockups')->insert(['type_id' => $type->id, 'position_id' => $position->id, 'siz_x_max' => 50, 'siz_y_max' => 50, 'siz_x_def' => 50, 'pos_x_min' => 25, 'pos_y_min' => 25, 'pos_x_def' => 50, 'pos_y_def' => 50]);
        }
        $editor->set('size', 50)->set('posX', 40)->call('save')->assertSet('saveStatus', 'saved');
        $editor->set('posX', 60)->call('save')->assertSet('saveStatus', 'saved');
        $this->assertSame(1, DB::table('items_previews')->where('product_id', $product->id)->where('position_id', $position->id)->count());
        $editor->set('size', 101)->call('save')->assertHasErrors('size');
    }

    public function test_vector_and_raster_uploads_validate_and_store_files(): void
    {
        Storage::fake('public');
        $design = $this->design();
        $pdf = UploadedFile::fake()->createWithContent('art.pdf', "%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\n%%EOF");
        $this->post(route('designs.upload-image', [$design, 'type' => 'vector']), ['file_vector' => $pdf])->assertSessionHasNoErrors();
        Storage::disk('public')->assertExists('designs_vector/'.$design->id.'.pdf');
        $this->post(route('designs.upload-image', [$design, 'type' => 'light']), ['file_light' => UploadedFile::fake()->image('art.png', 40, 20)])->assertSessionHasNoErrors();
        Storage::disk('public')->assertExists('designs/'.$design->id.'.png');
        $this->assertEquals(2, $design->fresh()->ratio);
        $this->post(route('designs.upload-image', [$design, 'type' => 'vector']), ['file_vector' => UploadedFile::fake()->create('bad.txt', 1, 'text/plain')])->assertSessionHasErrors('file_vector');
    }

    public function test_upload_failure_is_reported(): void
    {
        Storage::fake('public');
        $design = $this->design();
        $this->mock(ImageProcessor::class, function ($mock) {
            $mock->shouldReceive('upload')->once()->andReturn(false);
        });
        $this->post(route('designs.upload-image', [$design, 'type' => 'light']), ['file_light' => UploadedFile::fake()->image('art.png')])->assertSessionHasErrors('file_light');
    }

    public function test_custom_design_hides_standard_design_controls(): void
    {
        $design = $this->design();
        DB::table('designs')->where('id', $design->id)->update(['custom' => true]);
        $this->get(route('designs.edit', $design))->assertOk()->assertDontSee('Grafica scura')->assertDontSee('Grafica vettoriale')->assertDontSee('Colore di sfondo');
    }

    public function test_missing_mockup_and_empty_colors_render_without_errors(): void
    {
        $design = $this->design();
        $type = ProductType::where('hidden', 0)->whereHas('colors')->whereHas('positions', fn ($query) => $query->where('editable', true))->firstOrFail();
        $product = EditDesign::createProduct($design, $type);
        $position = $type->positions->firstWhere('editable', true);
        DB::table('items_mockups')->where('type_id', $type->id)->where('position_id', $position->id)->delete();
        Livewire::test(ProductPosition::class, ['product' => $product, 'positionId' => $position->id])->assertSee('configurazione di stampa mancante');
        $type->colors()->detach();
        $product->colors()->detach();
        Livewire::test(ProductColors::class, ['product' => $product->fresh(), 'positionId' => $position->id])->assertOk()->call('save')->assertHasErrors('activeColorIds');
    }

    public function test_product_mutations_reject_a_different_owner(): void
    {
        $design = $this->design();
        $type = ProductType::where('hidden', 0)->whereHas('colors')->whereHas('positions')->firstOrFail();
        $product = EditDesign::createProduct($design, $type);
        $otherDesign = $this->design();
        Livewire::test(ProductList::class, ['design' => $otherDesign])->call('toggleStatus', $product->id)->assertForbidden();
        Livewire::test(ProductColors::class, ['product' => $product, 'positionId' => $product->position_id])->assertForbidden();
        $this->post(route('products.status', $product), ['status' => 0])->assertForbidden();
    }

    public function test_valid_color_changes_and_design_default_color_are_persisted(): void
    {
        $design = $this->design();
        $type = ProductType::where('hidden', 0)->whereHas('colors')->whereHas('positions')->firstOrFail();
        $product = EditDesign::createProduct($design, $type);
        $colorId = (int) $type->colors->first()->id;
        Livewire::test(ProductColors::class, ['product' => $product, 'positionId' => $product->position_id])
            ->set('activeColorIds', [(string) $colorId])->set('defaultColorId', $colorId)->call('save')->assertHasNoErrors()->assertSet('saveStatus', 'saved')->assertDispatched('product-updated');
        $this->assertSame([$colorId], $product->fresh()->colors->modelKeys());
        Livewire::test(DefaultColor::class, ['design' => $design])
            ->set('value', (string) $colorId)->call('save')->assertHasNoErrors()->assertSet('saveStatus', 'saved')->assertDispatched('design-products-updated');
        $this->assertEquals($colorId, $design->fresh()->default_color_id);
    }
}
