<?php

namespace App\Livewire\Ecommerce\Designs\Edit;

use App\Actions\Designs\EditDesign;
use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductMockup;
use App\Models\Ecommerce\Product\ProductTypeMockupSize;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Vite;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class ProductPosition extends Component
{
    #[Locked]
    public int $productId;

    #[Locked]
    public int $positionId;

    public float $size = 25;

    public float $posX = 50;

    public float $posY = 50;

    public string $saveStatus = 'inactive';

    public function boot(): void
    {
        if (isset($this->productId)) {
            EditDesign::product($this->productId);
        }
    }

    public function mount(Product $product, int $positionId): void
    {
        $this->productId = $product->id;
        $this->positionId = $positionId;
        $product = $this->product();
        $config = $this->config($product);
        $mockup = $product->mockup->firstWhere('position_id', $positionId);
        $this->size = $mockup?->size ?? $config?->siz_x_def ?? 25;
        $this->posX = $mockup?->pos_x ?? $config?->pos_x_def ?? 50;
        $this->posY = $mockup?->pos_y ?? $config?->pos_y_def ?? 50;
    }

    private function product(): Product
    {
        $product = EditDesign::product($this->productId);
        abort_unless($product->type->positions->where('editable', true)->contains('id', $this->positionId), 403);

        return $product;
    }

    private function config(Product $product): ?ProductTypeMockupSize
    {
        return ProductTypeMockupSize::firstByTypeId($product->type_id, $this->positionId);
    }

    public function toggle(): void
    {
        DB::transaction(function () {
            $product = $this->product();
            Product::whereKey($product->id)->lockForUpdate()->firstOrFail();
            $ids = array_map('intval', array_filter(explode(',', $product->fresh()->positions ?? '')));
            if (in_array($this->positionId, $ids)) {
                if (count($ids) < 2) {
                    $this->addError('position', 'Deve rimanere attiva almeno una posizione.');

                    return;
                } $ids = array_diff($ids, [$this->positionId]);
            } else {
                $ids[] = $this->positionId;
            }
            $product->update(['positions' => implode(',', $ids)]);
            $product->deletePreviewImages();
            $this->dispatch('product-updated', productId: $product->id);
        });
    }

    public function save(): void
    {
        $this->saveStatus = 'inactive';
        $product = $this->product();
        abort_unless($this->config($product), 422);
        $this->validate(['size' => 'required|numeric|min:25|max:100', 'posX' => 'required|numeric|min:0|max:100', 'posY' => 'required|numeric|min:0|max:100']);
        if (! in_array($this->positionId, array_map('intval', explode(',', $product->positions)))) {
            $this->addError('position', 'Attiva questa posizione prima di salvarla.');

            return;
        }
        try {
            DB::transaction(function () use ($product) {
                Product::whereKey($product->id)->lockForUpdate()->firstOrFail();
                ProductMockup::updateOrCreate(['product_id' => $product->id, 'position_id' => $this->positionId], ['size' => $this->size, 'pos_x' => $this->posX, 'pos_y' => $this->posY]);
            });
            $product->deletePreviewImages();
            $this->saveStatus = 'saved';
            $this->dispatch('product-updated', productId: $product->id);
        } catch (\Throwable $exception) {
            report($exception);
            $this->saveStatus = 'error';
        }
    }

    #[On('design-products-updated')]
    public function refreshDefaultColor(): void {}

    #[On('product-updated')]
    public function refreshPreview(int $productId): void
    {
        if ($productId !== $this->productId) {
            $this->skipRender();
        }
    }

    public function render()
    {
        $product = $this->product();
        $config = $this->config($product);
        $position = $product->type->positions->firstWhere('id', $this->positionId);
        $enabled = in_array($this->positionId, array_map('intval', explode(',', $product->positions ?? '')));
        $light = Storage::disk('public')->exists('designs/'.$product->design_id.'.png') ? storage_asset('designs/'.$product->design_id.'.png') : Vite::image('placeholder.webp');
        $dark = Storage::disk('public')->exists('designs/'.$product->design_id.'_dark.png') ? storage_asset('designs/'.$product->design_id.'_dark.png') : $light;
        $clothing = ! $product->color ? null : $product->type->image($product->color_id, $position->basicPositionId());
        $details = ! $product->color ? null : $product->type->image($product->color_id, $position->basicPositionId(), '-det');
        $landscape = $config && $config->siz_y_max > 0 && $product->design->ratio > $config->siz_x_max / $config->siz_y_max;

        return view('livewire.ecommerce.designs.edit.product-position', compact('product', 'config', 'position', 'enabled', 'light', 'dark', 'clothing', 'details', 'landscape'));
    }
}
