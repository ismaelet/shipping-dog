<?php

namespace App\Livewire\UserDashboard\Settings;

use App\Helpers\ImageHelper;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadIcon extends Component {

	use WithFileUploads;

	public User $user;

	#[Validate('image|max:10240')]
	public $image;

	/**
	 * Initializes the component with the authenticated user's data.
	 */
	public function mount(): void {
		$this->user = Auth::user();
	}

	/**
	 * Handles the image upload and saves the icon.
	 * 
	 * This method is triggered when the image is updated.
	 */
	public function updatedImage() {
		$this->save();
		$this->dispatch('userIconUpdated');
	}

	/**
	 * Saves the uploaded icon image.
	 * 
	 * @return void
	 */
	public function save(): void {
		ImageHelper::upload(
			file: $this->image,
			path: 'users/icons/' . Auth::id() . '.webp',
			size: 252,
			mode: 'crop',
		);
	}

	/**
	 * Renders the component view for uploading an icon.
	 * 
	 * @return View
	 */
	public function render(): View {
		return view('livewire.user-dashboard.settings.upload-icon');
	}
}
