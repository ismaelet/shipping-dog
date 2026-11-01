<?php

namespace App\Livewire\Portfolio\Product;

use App\Actions\Portfolio\CustomProductAccess;
use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductType;
use App\Services\Files\ImageProcessor;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class Images extends Component
{
    use WithFileUploads;

    #[Locked]
    public ?int $productId = null;

    #[Locked]
    public ?string $tempId = null;

    #[Locked]
    public int $typeId;

    #[Locked]
    public array $colorIds = [];

    public array $uploads = [];

    public function boot(): void
    {
        if ($this->productId) {
            CustomProductAccess::authorize(Product::findOrFail($this->productId));
        } elseif ($this->tempId) {
            CustomProductAccess::authorizeTemporary($this->tempId);
        }
    }

    public function mount(?int $productId, ?string $tempId, int $typeId, array $colorIds): void
    {
        $this->productId = $productId;
        $this->tempId = $tempId;
        $this->authorizeUpload();
        $this->typeId = $typeId;
        $this->colorIds = array_values(array_intersect(array_map('intval', $colorIds), $this->type()->colors->modelKeys()));
    }

    private function authorizeUpload(): void
    {
        if ($this->productId) {
            CustomProductAccess::authorize(Product::findOrFail($this->productId));
        } else {
            CustomProductAccess::authorizeTemporary($this->tempId ?? '');
        }
    }

    private function type(): ProductType
    {
        $currentTypeId = $this->productId ? Product::findOrFail($this->productId)->type_id : null;

        return ProductType::whereKey($this->typeId)->where(function ($query) use ($currentTypeId) {
            $query->where('hidden', false);
            if ($currentTypeId) {
                $query->orWhere('id', $currentTypeId);
            }
        })->with('colors', 'all_positions')->firstOrFail();
    }

    #[On('custom-product-variants-updated')]
    public function updateVariants(int $typeId, array $colorIds): void
    {
        $this->authorizeUpload();
        $this->typeId = $typeId;
        $this->colorIds = array_values(array_intersect(array_map('intval', $colorIds), $this->type()->colors->modelKeys()));
        $this->reset('uploads');
    }

    public function updatedUploads($file, string $key): void
    {
        $this->authorizeUpload();
        $this->validateOnly('uploads.'.$key, ['uploads.'.$key => 'required|image|max:5120']);
        $parts = explode('.', $key);
        abort_unless(count($parts) === 2 && ctype_digit($parts[0]) && ctype_digit($parts[1]), 422);
        [$positionId, $colorId] = array_map('intval', $parts);
        $type = $this->type();
        $position = $type->all_positions->firstWhere('id', $positionId);
        $color = $type->colors->firstWhere('id', $colorId);
        abort_unless($position && ! $position->base_of && $color && in_array($colorId, $this->colorIds, true), 422);
        $prefix = $this->productId ? 'products/custom/'.$this->productId.'-' : 'products/temp/'.$this->tempId.'/';
        foreach ([1024 => 90, 400 => 80] as $size => $quality) {
            if (! app(ImageProcessor::class)->upload($file, $prefix.$color->tag.'-'.$positionId.'-'.$size.'.webp', $size, quality: $quality)) {
                $this->addError('uploads.'.$key, 'Caricamento non riuscito. Riprova.');

                return;
            }
        }
        unset($this->uploads[$positionId][$colorId]);
    }

    public function render()
    {
        $type = $this->type();
        $positions = $type->all_positions->filter(fn ($position) => ! $position->base_of);
        $colors = $type->colors->whereIn('id', $this->colorIds)->sortBy('reorder');
        $previews = [];
        foreach ($positions as $position) {
            foreach ($colors as $color) {
                $prefix = $this->productId ? 'products/custom/'.$this->productId.'-' : 'products/temp/'.$this->tempId.'/';
                $path = $prefix.$color->tag.'-'.$position->id.'-400.webp';
                $previews[$position->id][$color->id] = Storage::disk('public')->exists($path) ? storage_asset($path) : null;
            }
        }

        return view('livewire.portfolio.product.images', compact('positions', 'colors', 'previews'));
    }
}
