<?php

namespace App\Livewire;

use App\Helpers\FileHelper;
use App\Services\Files\ImageProcessor;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class UploadImage extends Component
{
    use WithFileUploads;

    /**
     * Property: the image processor service
     */
    private ImageProcessor $imageProcessor;

    /**
     * Property: the URL of the image to be uploaded
     */
    public string|array $url;

    /**
     * Property: the size of the image to be uploaded
     */
    public int|array $size;

    /**
     * Property: the quality of the image to be uploaded
     */
    public int|array $quality;

    /**
     * Property: the mode of the image to be uploaded
     */
    public string|array $mode = 'largest';

    /**
     * Property: the aspect ratio of the image to be uploaded
     */
    public string $aspect = 'square';

    /**
     * Property: the URL of the existing image (if any)
     */
    public ?string $imageUrl = null;

    /**
     * Property: the image file to be uploaded
     */
    #[Validate('required|image|max:102400')]
    public ?TemporaryUploadedFile $image = null;

    /**
     * Lifecycle hook: mount the component
     */
    public function mount()
    {
        $this->url = is_array($this->url) ? $this->url : [$this->url];
        $this->size = is_array($this->size) ? $this->size : [$this->size];
        $this->quality = is_array($this->quality) ? $this->quality : [$this->quality];
        $this->mode = is_array($this->mode) ? $this->mode : [$this->mode];

        $this->imageUrl = $this->url[0];
    }

    /**
     * Lifecycle hook: boot the component.
     */
    public function boot(ImageProcessor $imageProcessor): void
    {
        $this->imageProcessor = $imageProcessor;
    }

    /**
     * lifecycle hook: render the component
     */
    public function render()
    {
        return view('livewire.upload-image');
    }

    /**
     * Upload product image for a specific color
     */
    public function updatedImage(): void
    {
        $this->validateOnly('image');

        foreach ($this->url as $index => $url) {
            $this->uploadImage(
                $url,
                $this->size[$index] ?? 1024,
                $this->quality[$index] ?? 90,
                $this->mode[$index] ?? 'largest',
            );
        }

        $this->imageUrl = $this->url[0];

        unset($this->imageExists, $this->imageSrc);

        $this->image = null;
    }

    private function uploadImage(string $url, int $size, int $quality, string $mode): void
    {
        $this->imageProcessor->upload(
            file: $this->image,
            path: $url,
            size: $size,
            mode: $mode,
            quality: $quality
        );
    }

    #[Computed]
    public function imageExists(): bool
    {
        return $this->imageUrl !== null
            && FileHelper::exists($this->imageUrl);
    }

    #[Computed]
    public function imageSrc(): ?string
    {
        return $this->imageExists
            ? FileHelper::storageAsset($this->imageUrl)
            : null;
    }
}
