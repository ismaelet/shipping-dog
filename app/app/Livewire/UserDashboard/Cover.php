<?php

namespace App\Livewire\UserDashboard;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Cover extends Component
{
    public string $cover;

    /**
     * Initializes the component with the authenticated user's cover.
     */
    public function mount(): void
    {
        $this->cover = Auth::user()->cover();
    }

    /**
     * Listens for the 'userCoverUpdated' event to refresh the cover.
     */
    #[On('userCoverUpdated')]
    public function refreshCover(): void
    {
        $this->cover = Auth::user()->cover();
    }

    /**
     * Renders the cover view.
     */
    public function render(): View
    {
        return view('livewire.user-dashboard.cover');
    }
}
