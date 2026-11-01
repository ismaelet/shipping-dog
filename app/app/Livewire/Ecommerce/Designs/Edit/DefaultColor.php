<?php

namespace App\Livewire\Ecommerce\Designs\Edit;

use App\Actions\Designs\EditDesign;
use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Product\ProductColor;

class DefaultColor extends DesignEditor
{
    public string $value = '';

    public function mount(Design $design): void
    {
        EditDesign::authorize($design);
        $this->designId = $design->id;
        $this->value = (string) ($design->default_color_id ?? '');
    }

    public function save(): void
    {
        $this->saveStatus = 'inactive';
        $design = $this->design();
        $this->validate(['value' => ['required', 'integer', 'exists:product_colors,id']]);
        $this->persist(function () use ($design) {
            EditDesign::defaultColor($design, (int) $this->value);
            $this->dispatch('design-products-updated');
        });
    }

    public function render()
    {
        return view('livewire.ecommerce.designs.edit.default-color', ['colors' => ProductColor::where('hidden', 0)->orderBy('reorder')->orderBy('id')->get()]);
    }
}
