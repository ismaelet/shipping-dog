<?php

namespace App\Livewire\Ecommerce\Designs\Edit;

use App\Actions\Designs\EditDesign;
use App\Models\Ecommerce\Design;
use Livewire\Attributes\Locked;
use Livewire\Component;

abstract class DesignEditor extends Component
{
    #[Locked]
    public int $designId;

    public string $saveStatus = 'inactive';

    public function boot(): void
    {
        if (isset($this->designId)) {
            EditDesign::authorize(Design::findOrFail($this->designId));
        }
    }

    protected function design(): Design
    {
        $design = Design::findOrFail($this->designId);
        EditDesign::authorize($design);

        return $design;
    }

    public function updated(): void
    {
        $this->saveStatus = 'active';
    }

    protected function persist(callable $action): void
    {
        try {
            $action();
            $this->saveStatus = 'saved';
        } catch (\Throwable $exception) {
            report($exception);
            $this->saveStatus = 'error';
        }
    }
}
