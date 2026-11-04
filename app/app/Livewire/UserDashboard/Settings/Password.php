<?php

namespace App\Livewire\UserDashboard\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Livewire\Component;

class Password extends Component
{
    public User $user;

    public string $current_password;

    public string $new_password;

    public string $new_password_confirmation;

    /**
     * Initializes the component with the authenticated user's data.
     */
    public function mount(): void
    {
        $this->user = Auth::user();
    }

    /**
     * Saves the new password after validating the current password.
     */
    public function save(): void
    {
        $this->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string|min:8',
        ]);

        if (! password_verify($this->current_password, $this->user->password)) {
            $this->addError('current_password', 'La password attuale inserita non è corretta.');

            return;
        }

        $this->user->update([
            'password' => Hash::make($this->new_password),
        ]);

        session()->flash('success', 'Password aggiornata con successo.');

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
    }

    /**
     * Renders the password settings view.
     */
    public function render(): View
    {
        return view('livewire.user-dashboard.settings.password');
    }
}
