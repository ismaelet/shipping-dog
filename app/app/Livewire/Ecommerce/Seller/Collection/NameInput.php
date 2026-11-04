<?php

namespace App\Livewire\Ecommerce\Seller\Collection;

use App\Models\Ecommerce\Design\DesignCollection;
use App\Models\Ecommerce\Seller;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;

class NameInput extends Component
{
    /**
     * Property: The seller that owns the collection.
     */
    #[Locked]
    public Seller $seller;

    /**
     * Property: The collection being edited.
     */
    #[Locked]
    public DesignCollection $collection;

    /**
     * Property: The editable collection name.
     */
    public string $name = '';

    /**
     * Property: Whether the name has been saved successfully.
     */
    public bool $saved = false;

    /**
     * Mount the component.
     */
    public function mount(Seller $seller, DesignCollection $collection): void
    {
        $this->seller = $seller;
        $this->collection = $collection;
        $this->name = $collection->name;
    }

    /**
     * Update the collection name.
     */
    public function updatedName(): void
    {
        $this->authorizeCollectionUpdate();

        $name = trim($this->name);

        if ($name === '') {
            $this->saved = false;

            return;
        }

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $this->collection->update([
            'name' => $name,
        ]);

        $this->collection->refresh();

        $this->saved = true;

        $this->dispatch('collection-name-updated', name: $this->collection->name);
        $this->dispatch('collection-name-saved');
    }

    /**
     * Check whether the current user can update the collection.
     */
    private function authorizeCollectionUpdate(): void
    {
        $canUpdate = $this->collection->user_id === $this->seller->id
            && Auth::check()
            && Auth::id() === $this->seller->user_id;

        abort_unless($canUpdate, 403);
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('livewire.ecommerce.seller.collection.name-input');
    }
}
