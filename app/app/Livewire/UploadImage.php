<?php

namespace App\Livewire;

use App\Helpers\FileHelper;
use App\Services\Files\ImageProcessor;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class UploadImage extends Component {

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
     * Property: the URL of the existing image (if any)
     */
    public ?string $imageUrl = null;

    /**
     * Property: indicates whether the image already exists
     */
    public bool $imageExists = false;

    /**
     * Property: the image file to be uploaded
     */
    #[Validate('image')]
    public ?TemporaryUploadedFile $image = null;

    /**
     * Lifecycle hook: mount the component
     */
    public function mount() {
        $this->url = is_array($this->url) ? $this->url : [$this->url];
        $this->size = is_array($this->size) ? $this->size : [$this->size];
        $this->quality = is_array($this->quality) ? $this->quality : [$this->quality];
        $this->mode = is_array($this->mode) ? $this->mode : [$this->mode];

        $this->imageUrl = $this->url[0];

        $this->imageExists = FileHelper::exists($this->imageUrl);
    }

    /**
     * Lifecycle hook: boot the component.
     */
    public function boot(ImageProcessor $imageProcessor): void {
        $this->imageProcessor = $imageProcessor;
    }

    /**
     * lifecycle hook: render the component
     */
    public function render() {
        return view('livewire.upload-image');
    }

    /**
     * Upload product image for a specific color
     */
    public function updatedImage(): void {
        $this->validateOnly('image');

        foreach ($this->url as $index => $url) {
            $size = $this->size[$index] ?? 1024;
            $quality = $this->quality[$index] ?? 90;
            $mode = $this->mode[$index] ?? 'largest';

            $this->uploadImage($url, $size, $quality, $mode);
        }

        $this->imageExists = FileHelper::exists($this->imageUrl);
        $this->imageUrl = $this->url[0];
    }

    private function uploadImage(string $url, int $size, int $quality, string $mode): void {
        $this->imageProcessor->upload(
            file: $this->image,
            path: $url,
            size: $size,
            mode: $mode,
            quality: $quality
        );
    }
}
