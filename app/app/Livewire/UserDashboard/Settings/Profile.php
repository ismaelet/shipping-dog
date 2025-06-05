<?php

namespace App\Livewire\UserDashboard\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class Profile extends Component {

	public User $user;
	public string $email;
	public string $firstName;
	public string $lastName;

	/**
	 * Validation rules for the profile settings.
	 * 
	 * @var array<string, string>
	 */
	protected $rules = [
		'email' => 'required|email|max:255',
		'firstName' => 'nullable|string|max:255',
		'lastName' => 'nullable|string|max:255',
	];

	/**
	 * Initialize properties with the authenticated user's data.
	 * 
	 * @return void
	 */
	public function mount(): void {
		$this->user = Auth::user();
		$this->email = $this->user->email;
		$this->firstName = $this->user->first_name ?? '';
		$this->lastName = $this->user->last_name ?? '';
	}

	/**
	 * Save the user's profile settings.
	 * 
	 * @return void
	 */
	public function save(): void {
		$this->validate();

		if ($this->email !== $this->user->email && User::where('email', $this->email)->exists()) {
			$this->addError('email', 'L\'email inserita è già in uso da un altro utente.');

			return;
		}

		$this->user->update([
			'email' => $this->email,
			'first_name' => $this->firstName,
			'last_name' => $this->lastName,
		]);

		session()->flash('success', 'Dati aggiornati con successo.');
	}

	/**
	 * Render the component view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function render(): View {
		return view('livewire.user-dashboard.settings.profile');
	}
}
