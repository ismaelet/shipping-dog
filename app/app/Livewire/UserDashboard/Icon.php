<?php

namespace App\Livewire\UserDashboard;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Icon extends Component {

    public string $icon;

    private User $user;

    /**
     * Initializes the component with the authenticated user's data.
     * 
     * @return void
     */
    public function mount(): void {
        $this->user = Auth::user();
        $this->icon = $this->user->icon();
    }

    /**
     * Listens for the 'userIconUpdated' event to refresh the icon.
     * 
     * @return void
     */
    #[On('userIconUpdated')]
    public function updatedIcon(): void {
    }

    /**
     * Renders the icon view.
     * 
     * @return View
     */
    public function render(): View {
        return view('livewire.user-dashboard.icon');
    }
}
