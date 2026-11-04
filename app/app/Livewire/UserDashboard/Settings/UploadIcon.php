<?php

namespace App\Livewire\UserDashboard\Settings;

use App\Models\User;
use App\Services\Files\ImageProcessor;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class UploadIcon extends Component
{
    use WithFileUploads;

    /**
     * Property: the image processor service
     */
    private ImageProcessor $imageProcessor;

    /**
     * Property: the authenticated user
     */
    public User $user;

    /**
     * Property: the image file to be uploaded
     */
    #[Validate('image|max:10240')]
    public ?TemporaryUploadedFile $image = null;

    /**
     * Lifecycle hook: mount the component and set the authenticated user
     */
    public function mount(): void
    {
        $this->user = Auth::user();
    }

    /**
     * Lifecycle hook: boot the component and inject services
     */
    public function boot(ImageProcessor $imageProcessor): void
    {
        $this->imageProcessor = $imageProcessor;
    }

    /**
     * Lifecycle hook: render the component view for uploading an icon
     */
    public function render(): View
    {
        return view('livewire.user-dashboard.settings.upload-icon');
    }

    /**
     * Lifecycle hook: handle the image upload when the image property is updated
     */
    public function updatedImage(): void
    {
        $this->validateOnly('image');

        if (! $this->image) {
            return;
        }

        if (! $this->save()) {
            $this->addError('image', 'Errore durante il caricamento dell’immagine.');

            return;
        }

        $this->dispatch('userIconUpdated');
    }

    /**
     * Helper method: save the uploaded image using the image processor service
     */
    public function save(): bool
    {
        return $this->imageProcessor->upload(
            file: $this->image,
            path: 'users/icons/'.Auth::id().'.webp',
            size: 252,
            mode: 'crop',
        );
    }
}
