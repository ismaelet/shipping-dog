<?php

namespace App\Livewire\Ecommerce\Designs\Edit;

use App\Actions\Designs\EditDesign;
use App\Models\Ecommerce\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Vite;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class ProductColors extends Component
{
    /**
     * Property: ID of the product being edited
     */
    #[Locked]
    public int $productId;

    /**
     * Property: ID of the product position used in the preview
     */
    #[Locked]
    public int $positionId;

    /**
     * Property: IDs of the active colors
     */
    public array $activeColorIds = [];

    /**
     * Property: ID of the color used for product previews
     */
    public int $defaultColorId = 0;

    public function boot(): void
    {
        if (isset($this->productId)) {
            EditDesign::product($this->productId);
        }
    }

    #[On('product-updated')]
    public function refreshPreview(int $productId): void
    {
        if ($productId === $this->productId) {
            $this->positionId = (int) $this->product->position_id;
        } else {
            $this->skipRender();
        }
    }

    #[On('design-products-updated')]
    public function refreshDefaultColor(): void
    {
        $this->defaultColorId = (int) $this->product->color_id;
    }

    /**
     * Property: Indicates if there are unsaved changes
     */
    public string $saveStatus = 'inactive';

    /**
     * Life cycle method: Initialize the component
     */
    public function mount(Product $product, int $positionId): void
    {
        EditDesign::product($product->id);
        $this->productId = $product->id;
        $this->positionId = $positionId;

        $this->activeColorIds = $product->colors
            ->pluck('id')
            ->map(fn (int|string $id): int => (int) $id)
            ->values()
            ->all();

        $this->defaultColorId = (int) $product->color_id;

        $this->normalizeColorSelection();
    }

    /**
     * Return the product.
     */
    public function getProductProperty(): Product
    {
        return EditDesign::product($this->productId);
    }

    /**
     * Return the colors available for the product type.
     */
    public function getColorsProperty(): Collection
    {
        return $this->product->type->colors
            ->sortBy([
                ['reorder', 'asc'],
                ['preorder', 'asc'],
                ['hidden', 'asc'],
            ]);
    }

    /**
     * Handle changes to active colors.
     */
    public function updatedActiveColorIds(): void
    {
        $this->activeColorIds = array_map(fn ($id) => is_numeric($id) ? (int) $id : $id, $this->activeColorIds);
        $this->markAsChanged();
    }

    /**
     * Handle changes to the default color.
     */
    public function updatedDefaultColorId(): void
    {
        $this->activeColorIds = array_map(fn ($id) => is_numeric($id) ? (int) $id : $id, $this->activeColorIds);
        $this->defaultColorId = (int) $this->defaultColorId;

        if (! in_array($this->defaultColorId, $this->activeColorIds, true)) {
            $this->activeColorIds[] = $this->defaultColorId;
        }

        $this->markAsChanged();
    }

    /**
     * Ensure that the selected colors are valid.
     */
    private function normalizeColorSelection(): void
    {
        $availableColorIds = $this->availableColorIds();

        $this->activeColorIds = collect($this->activeColorIds)
            ->map(fn (int|string $id): int => (int) $id)
            ->filter(
                fn (int $id): bool => in_array(
                    $id,
                    $availableColorIds,
                    true,
                ),
            )
            ->unique()
            ->values()
            ->all();

        if ($this->activeColorIds === []) {
            $fallbackColorId = in_array(
                $this->defaultColorId,
                $availableColorIds,
                true,
            )
                ? $this->defaultColorId
                : ($availableColorIds[0] ?? null);

            if ($fallbackColorId !== null) {
                $this->activeColorIds = [$fallbackColorId];
            }
        }

        if (! in_array(
            $this->defaultColorId,
            $this->activeColorIds,
            true,
        )) {
            $this->defaultColorId = $this->activeColorIds[0] ?? 0;
        }
    }

    /**
     * Return the IDs of the colors available for the product type.
     */
    private function availableColorIds(): array
    {
        return $this->product->type->colors
            ->pluck('id')
            ->map(fn (int|string $id): int => (int) $id)
            ->values()
            ->all();
    }

    /**
     * Validate the selected colors.
     */
    private function validateColorSelection(): void
    {
        $availableColorIds = $this->availableColorIds();

        $this->validate([
            'activeColorIds' => [
                'required',
                'array',
                'min:1',
            ],
            'activeColorIds.*' => [
                'integer',
                'distinct',
                function (
                    string $attribute,
                    mixed $value,
                    \Closure $fail,
                ) use ($availableColorIds): void {
                    if (! in_array((int) $value, $availableColorIds, true)) {
                        $fail(
                            'Il colore selezionato non è disponibile per questo prodotto.',
                        );
                    }
                },
            ],
            'defaultColorId' => [
                'required',
                'integer',
                function (
                    string $attribute,
                    mixed $value,
                    \Closure $fail,
                ): void {
                    if (! in_array(
                        (int) $value,
                        $this->activeColorIds,
                        true,
                    )) {
                        $fail(
                            'Il colore predefinito deve essere uno dei colori attivi.',
                        );
                    }
                },
            ],
        ]);
    }

    /**
     * Persist the active colors and default color.
     */
    private function persistColors(): Product
    {
        return DB::transaction(function (): Product {
            $product = Product::query()
                ->with('type.colors')
                ->findOrFail($this->productId);

            $product->colors()->sync($this->activeColorIds);

            $product->update([
                'color_id' => $this->defaultColorId,
            ]);

            return $product->refresh();
        });
    }

    /**
     * Lifecycle hook: Render the component
     */
    public function render(): View
    {
        return view('livewire.ecommerce.designs.edit.product-colors');
    }

    /**
     * Action: Save the selected colors and default color for the product
     */
    public function save(): void
    {
        $this->saveStatus = 'inactive';
        $this->activeColorIds = array_map(fn ($id) => is_numeric($id) ? (int) $id : $id, $this->activeColorIds);
        $this->validateColorSelection();

        try {
            $product = $this->persistColors();

            $product->deletePreviewImages();
            $this->dispatch('product-updated', productId: $product->id);

            $this->saveStatus = 'saved';
        } catch (Throwable $exception) {
            report($exception);

            $this->saveStatus = 'error';
        }
    }

    /**
     * Helper method: mark the form as changed
     */
    private function markAsChanged(): void
    {
        $this->saveStatus = 'active';
    }

    /**
     * Helper method: return the current preview image URL
     */
    public function getPreviewImageProperty(): string
    {
        if (! $this->defaultColorId || ! $this->product->type->colors->contains($this->defaultColorId)) {
            return Vite::image('placeholder.webp');
        }

        return EditDesign::preview($this->product, $this->defaultColorId, $this->positionId);
    }
}
