<?php

namespace App\Livewire\Ecommerce\Designs\Edit;

use App\Actions\Designs\EditDesign;
use App\Models\Ecommerce\Design;

class Name extends DesignEditor
{
    public string $value = '';

    public function mount(Design $design): void
    {
        EditDesign::authorize($design);
        $this->designId = $design->id;
        $this->value = $design->name;
    }

    public function save(): void
    {
        $this->saveStatus = 'inactive';
        $design = $this->design();
        if ($this->value === $design->name) {
            $this->resetValidation('value');

            return;
        }

        $this->validate(['value' => ['required', 'string', 'max:25']]);
        $this->persist(function () use ($design) {
            $design->update(['name' => $this->value]);
        });

        if ($this->saveStatus === 'saved') {
            $this->dispatch('design-name-saved', designId: $this->designId);
        }
    }

    public function render()
    {
        return view('livewire.ecommerce.designs.edit.name', []);
    }
}
