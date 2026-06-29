<?php

namespace App\Services\Files;

use App\Helpers\FileHelper;
use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductColor;
use App\Models\Ecommerce\Product\ProductMockup;
use App\Models\Ecommerce\Product\ProductPosition;
use App\Models\Ecommerce\Product\ProductType;
use App\Models\Ecommerce\Product\ProductTypeMockupSize;
use GdImage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductPreviewProcessor {

    /**
     * Constant: the small image size for product previews
     */
    public const SMALL_IMAGE_SIZE = 400;

    /**
     * Constant: the big image size for product previews
     */
    public const BIG_IMAGE_SIZE = 1024;

    /**
     * Constant: the default image size for product previews
     */
    public const DEFAULT_IMAGE_SIZE = self::SMALL_IMAGE_SIZE;

    /**
     * Constant: the allowed image sizes for product previews
     */
    public const ALLOWED_IMAGE_SIZES = [self::SMALL_IMAGE_SIZE, self::BIG_IMAGE_SIZE];

    /**
     * Constant: the placeholder image path for product previews
     */
    public const PLACEHOLDER_IMAGE = 'product_types/mockups/no-image.webp';

    /**
     * Property: the image size for the product preview
     */
    private int $imageSize;

    /**
     * Property: the product instance for which the preview is being generated
     */
    private Product $product;

    /**
     * Property: the product color instance for which the preview is being generated
     */
    private ?ProductColor $color = null;

    /**
     * Property: the product position instance for which the preview is being generated
     */
    private ?ProductPosition $position = null;

    /**
     * Returns the product preview image URL
     */
    public function image(
        Product $product,
        ProductColor|int|null $color = null,
        ProductPosition|int|null $position = null,
        ?int $imageSize = self::SMALL_IMAGE_SIZE,
    ): string {
        $this->setContext($product, $color, $position, $imageSize);

        if ($this->color && $this->position) {
            $image = $this->fileStoragePath();

            if ($this->hasImage()) {
                return FileHelper::storageAsset($image);
            }

            if ($this->product->id && !$this->product->isCustom()) {
                $this->generateImage();
            }

            if ($this->hasImage()) {
                return FileHelper::storageAsset($image);
            }
        }

        return FileHelper::storageAsset(self::PLACEHOLDER_IMAGE);
    }

    /**
     * Check whether the product preview image already exists.
     */
    public function exists(
        Product $product,
        ProductColor|int|null $color = null,
        ProductPosition|int|null $position = null,
        ?int $imageSize = self::SMALL_IMAGE_SIZE,
    ): bool {
        $this->setContext($product, $color, $position, $imageSize);

        return $this->color && $this->position && $this->hasImage();
    }

    /**
     * Set the current product preview context.
     */
    private function setContext(
        Product $product,
        ProductColor|int|null $color = null,
        ProductPosition|int|null $position = null,
        ?int $imageSize = self::SMALL_IMAGE_SIZE,
    ): void {
        $position = $position ?? $product->type->unique_positions()->first();

        $this->imageSize = in_array($imageSize, self::ALLOWED_IMAGE_SIZES, true)
            ? $imageSize
            : self::DEFAULT_IMAGE_SIZE;

        $this->product = $product;

        $this->color = match (true) {
            $color instanceof ProductColor => $color,
            is_int($color) => ProductColor::find($color) ?? $product->color,
            default => $product->color,
        };

        $this->position = match (true) {
            $position instanceof ProductPosition => $position,
            is_int($position) => ProductPosition::find($position) ?? $product->position,
            default => $product->position,
        };
    }

    private function fileName(): string {
        return $this->product->id . '-' . $this->color->tag . '-' . $this->position->id . '-' . $this->imageSize . '.webp';
    }

    private function storageDirectory(): string {
        return !$this->product->isCustom() ? 'products/' . $this->product->type->tag : 'products/custom';
    }

    private function fileStoragePath(): string {
        return $this->storageDirectory() . '/' . $this->fileName();
    }

    private function fileServerPath(): string {
        return Storage::disk('public')->path($this->fileStoragePath());
    }

    private function hasImage(): bool {
        return Storage::disk('public')->exists($this->fileStoragePath());
    }

    /**
     * Generate the image
     */
    private function generateImage(): void {
        $design = $this->product->design_with_trashed;

        // Fetch mockup type data
        $mockupFrameworkData = ProductTypeMockupSize::where('type_id', $this->product->type_id)->where('position_id', $this->position->id)->first();

        // Fetch mockup product data
        $mockupProductData = ProductMockup::where('product_id', $this->product->id)->where('position_id', $this->position->id)->first();

        // Background image
        $backgroundImage = Storage::disk('public')->path('product_types/mockups/bgr/grey.png');

        // Clothing images
        $clothingImage = Storage::disk('public')->path(
            ltrim($this->product->type->image($this->color->id, $this->position->id), '/')
        );

        $clothingImageExtension = pathinfo($clothingImage, PATHINFO_EXTENSION);

        $details = file_exists($file = str_replace(
            '.' . $clothingImageExtension,
            '-det.' . $clothingImageExtension,
            $clothingImage
        )) ? $file : false;

        // Design image
        $darkDesignImage = Storage::disk('public')->path('designs/' . $design->id . '_dark.png');
        $lightDesignImage = Storage::disk('public')->path('designs/' . $design->id . '.png');

        $designImage = $this->color->dark && file_exists($darkDesignImage)
            ? $darkDesignImage
            : $lightDesignImage;

        // Check if every required image exists
        if (!file_exists($clothingImage)) {
            error_log('Doesn\'t exist clothing file: ' . $clothingImage);

            if (config('app.debug')) {
                dd('Doesn\'t exist clothing file: ' . $clothingImage, $this->product->type->image($this->color->id, $this->position->id), $this->color, $this->position);
            }

            return;
        }

        if (!file_exists($designImage)) {
            error_log('Doesn\'t exist design file: ' . $designImage . ' of product: ' . $this->product->id);

            if (config('app.debug')) {
                // dd('Doesn\'t exist design file: ' . $designImage, $design->id, $this->color, $this->position);
            }

            return;
        }

        if (!$mockupProductData && !$mockupFrameworkData) {
            error_log('Doesn\'t exist mockup data for product: ' . $this->product->id);

            if (config('app.debug')) {
                dd('Doesn\'t exist mockup data for product: ' . $this->product->id, $this->product->type->name, $this->color->name, $this->position->name);
            }

            return;
        }

        // Create the directory if it doesn't exist
        Storage::disk('public')->makeDirectory($this->storageDirectory());

        // Create the base image
        $x = $y = $this->imageSize;
        $out = imagecreatetruecolor($x, $y);

        // Add the background
        if (
            !$this->copyLayer(
                canvas: $out,
                sourcePath: $backgroundImage,
                destinationX: 0,
                destinationY: 0,
                destinationWidth: $x,
                destinationHeight: $y,
            )
        ) {
            Log::error('No valid background image for product preview', [
                'image' => $backgroundImage,
            ]);

            imagedestroy($out);

            return;
        }

        // Add the clothing image
        if (
            !$this->copyLayer(
                canvas: $out,
                sourcePath: $clothingImage,
                destinationX: 0,
                destinationY: 0,
                destinationWidth: $x,
                destinationHeight: $y,
            )
        ) {
            Log::error('No valid clothing image for product preview', [
                'image' => $clothingImage,
                'product_id' => $this->product->id,
                'type' => $this->product->type->name ?? null,
                'color' => $this->color->name ?? null,
                'position' => $this->position->name ?? null,
            ]);

            imagedestroy($out);

            return;
        }

        // Add the design image
        $designDimensions = $this->imageDimensions($designImage);

        if ($designDimensions === null) {
            Log::error('No valid design image for product preview', [
                'image' => $designImage,
                'product_id' => $this->product->id,
            ]);

            imagedestroy($out);

            return;
        }

        $src_w = $designDimensions['width'];
        $src_h = $designDimensions['height'];

        $sX = $sY = $mockupProductData ? $mockupProductData->size / 100 : $mockupFrameworkData->siz_x_def / 100;
        $pX = $mockupProductData ? $mockupProductData->pos_x / 100 : $mockupFrameworkData->pos_x_def / 100;
        $pY = $mockupProductData ? $mockupProductData->pos_y / 100 : $mockupFrameworkData->pos_y_def / 100;
        $pX_min = $mockupFrameworkData->pos_x_min / 100;
        $pY_min = $mockupFrameworkData->pos_y_min / 100;
        $sX_max = $mockupFrameworkData->siz_x_max / 100;
        $sY_max = $mockupFrameworkData->siz_y_max / 100;

        $ratio = $src_w / $src_h;
        $land = $ratio > $sX_max / $sY_max;

        if ($land) {
            $des_w = (int) round($x * $sX_max * $sX);
            $des_h = (int) round($des_w / $ratio);
        } else {
            $des_h = (int) round($y * $sY_max * $sY);
            $des_w = (int) round($des_h * $ratio);
        }

        $des_x = (int) round(($x * $pX_min) + ($x * $sX_max - $des_w) * $pX);
        $des_y = (int) round(($y * $pY_min) + (($y * $sY_max - $des_h)) * $pY);

        if (
            !$this->copyLayer(
                canvas: $out,
                sourcePath: $designImage,
                destinationX: $des_x,
                destinationY: $des_y,
                destinationWidth: $des_w,
                destinationHeight: $des_h,
            )
        ) {
            Log::error('Could not copy design layer for product preview', [
                'image' => $designImage,
                'product_id' => $this->product->id,
            ]);

            imagedestroy($out);

            return;
        }

        // Add the details
        if ($details) {
            $this->copyLayer(
                canvas: $out,
                sourcePath: $details,
                destinationX: 0,
                destinationY: 0,
                destinationWidth: $x,
                destinationHeight: $y,
            );
        }

        // Generate the image
        $saved = $this->saveWebp(
            image: $out,
            path: $this->fileServerPath(),
            quality: $this->imageSize >= 1000 ? 90 : 70,
        );

        if (!$saved) {
            Log::error('Could not save product preview image', [
                'path' => $this->fileServerPath(),
                'product_id' => $this->product->id,
            ]);
        }

        imagedestroy($out);
    }

    /**
     * Generate empty image
     */
    public function emptyImage(
        ProductType $type,
        ProductColor $color,
        ProductPosition $position,
        int $imageSize = 1024,
    ): ?string {
        $directory = 'product_types/rasters';
        $fileName = $type->tag . '-' . $color->tag . '-' . $position->id . '-' . $imageSize . '.webp';
        $filePath = Storage::disk('public')->path($directory . '/' . $fileName);

        if (FileHelper::exists($directory . '/' . $fileName)) {
            return FileHelper::storageAsset($directory . '/' . $fileName);
        }

        // Background image
        $backgroundImage = Storage::disk('public')->path('product_types/mockups/bgr/grey.png');

        // Clothing images
        $clothingImage = Storage::disk('public')->path(
            ltrim($type->image($color->id, $position->id), '/')
        );

        $clothingImageExtension = pathinfo($clothingImage, PATHINFO_EXTENSION);

        $details = file_exists($file = str_replace(
            '.' . $clothingImageExtension,
            '-det.' . $clothingImageExtension,
            $clothingImage
        )) ? $file : false;

        // Check if every required image exists
        if (!file_exists($clothingImage)) {
            error_log('Doesn\'t exist clothing file: ' . $clothingImage);
            return null;
        }

        // Create the directory if it doesn't exist
        Storage::disk('public')->makeDirectory($directory);

        // Create the base image
        $x = $y = $imageSize;
        $out = imagecreatetruecolor($x, $y);

        // Add the background
        if (
            !$this->copyLayer(
                canvas: $out,
                sourcePath: $backgroundImage,
                destinationX: 0,
                destinationY: 0,
                destinationWidth: $x,
                destinationHeight: $y,
            )
        ) {
            Log::error('No valid background image for product preview', [
                'image' => $backgroundImage,
            ]);

            imagedestroy($out);

            return null;
        };

        // Add the clothing image
        if (
            !$this->copyLayer(
                canvas: $out,
                sourcePath: $clothingImage,
                destinationX: 0,
                destinationY: 0,
                destinationWidth: $x,
                destinationHeight: $y,
            )
        ) {
            Log::error('No valid clothing image for empty product preview', [
                'image' => $clothingImage,
                'type' => $type->name ?? null,
                'color' => $color->name ?? null,
                'position' => $position->name ?? null,
            ]);

            imagedestroy($out);

            return null;
        }

        // Add the details
        if ($details) {
            $this->copyLayer(
                canvas: $out,
                sourcePath: $details,
                destinationX: 0,
                destinationY: 0,
                destinationWidth: $x,
                destinationHeight: $y,
            );
        }

        // Generate the image
        $saved = $this->saveWebp(
            image: $out,
            path: $this->fileServerPath(),
            quality: $this->imageSize >= 1000 ? 90 : 70,
        );

        if (!$saved) {
            Log::error('Could not save product preview image', [
                'path' => $this->fileServerPath(),
                'product_id' => $this->product->id,
            ]);

            imagedestroy($out);

            return null;
        }

        imagedestroy($out);

        return FileHelper::storageAsset($directory . '/' . $fileName);
    }

    /**
     * Delete all generated preview images for a product
     */
    public function deleteProductPreview(Product $product): void {
        $directory = 'products/' . $product->type->tag;

        if (!Storage::disk('public')->exists($directory)) {
            return;
        }

        $files = Storage::disk('public')->files($directory);

        foreach ($files as $file) {
            if (str_starts_with(basename($file), $product->id . '-')) {
                FileHelper::delete($file);
            }
        }
    }

    /**
     * Delete all images related to a specific product type
     */
    public function deleteByProductType(ProductType $type): void {
        $directory = 'products/' . $type->tag;

        if (!Storage::disk('public')->exists($directory)) {
            return;
        }

        foreach (Storage::disk('public')->files($directory) as $file) {
            FileHelper::delete($file);
        }
    }

    /**
     * Helper method: create a GD image from a file path
     */
    private function createImageFromPath(string $path): GdImage|false {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return match ($extension) {
            'webp' => @imagecreatefromwebp($path),
            'png' => @imagecreatefrompng($path),
            'jpg', 'jpeg' => @imagecreatefromjpeg($path),
            default => false,
        };
    }

    /**
     * Helper method: copy a layer from a source image to the canvas with resizing
     */
    private function copyLayer(
        GdImage $canvas,
        string $sourcePath,
        int $destinationX,
        int $destinationY,
        int $destinationWidth,
        int $destinationHeight,
    ): bool {
        $sourceImage = $this->createImageFromPath($sourcePath);

        if (!$sourceImage) {
            return false;
        }

        $sourceSize = getimagesize($sourcePath);

        if (!$sourceSize || !isset($sourceSize[0], $sourceSize[1])) {
            imagedestroy($sourceImage);

            return false;
        }

        $copied = imagecopyresampled(
            $canvas,
            $sourceImage,
            $destinationX,
            $destinationY,
            0,
            0,
            $destinationWidth,
            $destinationHeight,
            $sourceSize[0],
            $sourceSize[1],
        );

        imagedestroy($sourceImage);

        return $copied;
    }

    /**
     * Helper method: get image dimensions from a file path.
     */
    private function imageDimensions(string $path): ?array {
        $size = getimagesize($path);

        if (!$size || !isset($size[0], $size[1])) {
            return null;
        }

        return [
            'width' => $size[0],
            'height' => $size[1],
        ];
    }

    /**
     * Helper method: save a GD image as a WebP file with specified quality
     */
    private function saveWebp(GdImage $image, string $path, int $quality): bool {
        return imagewebp($image, $path, $quality);
    }
}
