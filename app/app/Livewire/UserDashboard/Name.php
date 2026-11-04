<?php

namespace App\Livewire\UserDashboard;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Name extends Component
{
    public string $fullName;

    public string $email;

    private User $user;

    /**
     * Initializes the component with the authenticated user's data.
     */
    public function boot(): void
    {
        $this->user = Auth::user();
        $this->fullName = $this->user->fullName();
        $this->email = $this->user->email;
    }

    /**
     * Listens for the 'userNameUpdated' event to refresh the name view.
     */
    #[On('userNameUpdated')]
    public function updatedName(): void {}

    /**
     * Renders the name view.
     */
    public function render(): View
    {
        return view('livewire.user-dashboard.name');
    }
}
