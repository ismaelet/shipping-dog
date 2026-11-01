<?php

namespace App\Livewire\Ecommerce\Designs\Edit;

use App\Actions\Designs\EditDesign;
use App\Models\Ecommerce\Design;

class Tags extends DesignEditor
{
    public string $value = '';

    public function mount(Design $design): void
    {
        EditDesign::authorize($design);
        $this->designId = $design->id;
        $this->value = $design->tags?->tags ?? '';
    }

    public function save(): void
    {
        $this->saveStatus = 'inactive';
        $design = $this->design();
        $this->validate(['value' => ['nullable', 'string', 'max:255']]);
        $this->persist(function () use ($design) {
            EditDesign::tags($design, $this->value);
        });
    }

    public function render()
    {
        return view('livewire.ecommerce.designs.edit.tags', []);
    }
}
