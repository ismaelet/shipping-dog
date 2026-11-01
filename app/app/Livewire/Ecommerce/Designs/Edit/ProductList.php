<?php

namespace App\Livewire\Ecommerce\Designs\Edit;

use App\Actions\Designs\EditDesign;
use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductType;
use Livewire\Attributes\On;

class ProductList extends DesignEditor
{
    public ?int $selectedProductId = null;

    public function mount(Design $design): void
    {
        EditDesign::authorize($design);
        $this->designId = $design->id;
    }

    public function createProduct(int $typeId): void
    {
        EditDesign::createProduct($this->design(), ProductType::findOrFail($typeId));
    }

    public function toggleStatus(int $id): void
    {
        $product = $this->ownedProduct($id);
        EditDesign::status($product, ! $product->status);
    }

    private function ownedProduct(int $id): Product
    {
        $product = EditDesign::product($id);
        abort_unless((int) $product->design_id === $this->designId, 403);

        return $product;
    }

    public function editProduct(int $id): void
    {
        $product = $this->ownedProduct($id);
        abort_unless($product->status, 422);
        $this->selectedProductId = $id;
        $this->dispatch('open-modal', name: 'design-product-editor');
    }

    #[On('product-updated')]
    #[On('design-products-updated')]
    public function refreshProducts(): void {}

    public function render()
    {
        $design = $this->design();
        $products = Product::where('design_id', $design->id)->with('type.colors', 'type.positions', 'design', 'design_with_trashed', 'color', 'position')->get()->keyBy('type_id');
        $types = ProductType::where('hidden', 0)->with('colors', 'positions')->orderBy('category')->orderBy('sort')->orderBy('id')->get();
        $previews = $products->mapWithKeys(fn (Product $product) => [$product->id => EditDesign::preview($product)]);
        $selected = $this->selectedProductId ? $this->ownedProduct($this->selectedProductId) : null;

        return view('livewire.ecommerce.designs.edit.product-list', compact('products', 'types', 'selected', 'previews'));
    }
}
