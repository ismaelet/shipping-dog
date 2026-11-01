<?php

namespace App\Livewire\Ecommerce\Designs\Edit;

use App\Actions\Designs\EditDesign;
use App\Models\Ecommerce\Design;

class BackgroundColor extends DesignEditor
{
    public string $value = '';

    public function mount(Design $design): void
    {
        EditDesign::authorize($design);
        $this->designId = $design->id;
        $this->value = $design->color ?? '#ffffff';
    }

    public function save(): void
    {
        $this->saveStatus = 'inactive';
        $design = $this->design();
        $this->validate(['value' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/']]);
        $this->persist(function () use ($design) {
            EditDesign::background($design, $this->value);
        });
    }

    public function render()
    {
        return view('livewire.ecommerce.designs.edit.background-color', []);
    }
}
