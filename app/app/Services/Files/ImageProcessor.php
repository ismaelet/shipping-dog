<?php

namespace App\Services\Files;

use GdImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ImageProcessor {

    /**
     * Upload and optionally resize an image.
     */
    public function upload(
        UploadedFile|TemporaryUploadedFile $file,
        string $path,
        int $size,
        string $mode = 'largest',
        int $quality = 80,
    ): bool {
        if ($size <= 0) {
            return false;
        }

        $quality = max(0, min(100, $quality));

        $directory = dirname($path);

        if ($directory !== '.') {
            Storage::disk('public')->makeDirectory($directory);
        }

        Storage::disk('public')->delete($path);

        $originalImage = $this->createFromFile($file);

        if (!$originalImage) {
            return false;
        }

        $dimensions = $this->calculateDimensions($originalImage, $size, $mode);

        if ($dimensions === null) {
            imagedestroy($originalImage);

            return false;
        }

        $type = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if ($type === 'jpeg') {
            $type = 'jpg';
        }

        if (!in_array($type, ['jpg', 'png', 'webp'], true)) {
            imagedestroy($originalImage);

            return false;
        }

        $canvas = $this->createCanvas($dimensions[0], $dimensions[1], $type);

        $created = $this->create(
            originalImage: $originalImage,
            canvas: $canvas,
            path: $path,
            type: $type,
            dimensions: $dimensions,
            quality: $quality,
        );

        imagedestroy($originalImage);
        imagedestroy($canvas);

        return $created && Storage::disk('public')->exists($path);
    }

    /**
     * Create a GD image from an uploaded file.
     */
    private function createFromFile(UploadedFile|TemporaryUploadedFile $file): GdImage|false {
        $filePath = $file->getRealPath();

        if (!$filePath) {
            return false;
        }

        $info = getimagesize($filePath);
        $imageType = $info[2] ?? null;

        return match ($imageType) {
            IMAGETYPE_WEBP => imagecreatefromwebp($filePath),
            IMAGETYPE_PNG => imagecreatefrompng($filePath),
            IMAGETYPE_JPEG => imagecreatefromjpeg($filePath),
            default => false,
        };
    }

    /**
     * Calculate resized/cropped dimensions.
     *
     * Returns:
     * [newWidth, newHeight, sourceX, sourceY, sourceWidth, sourceHeight]
     */
    private function calculateDimensions(GdImage $originalImage, int $newSize, string $mode): ?array {
        $originalWidth = imagesx($originalImage);
        $originalHeight = imagesy($originalImage);

        if ($originalWidth <= 0 || $originalHeight <= 0) {
            return null;
        }

        $ratio = $originalWidth / $originalHeight;

        $dimensions = match ($mode) {
            'width' => [
                $newSize,
                $newSize / $ratio,
                0,
                0,
                $originalWidth,
                $originalHeight,
            ],

            'height' => [
                $newSize * $ratio,
                $newSize,
                0,
                0,
                $originalWidth,
                $originalHeight,
            ],

            'largest' => $ratio > 1
                ? [$newSize, $newSize / $ratio, 0, 0, $originalWidth, $originalHeight]
                : [$newSize * $ratio, $newSize, 0, 0, $originalWidth, $originalHeight],

            'smallest' => $ratio > 1
                ? [$newSize * $ratio, $newSize, 0, 0, $originalWidth, $originalHeight]
                : [$newSize, $newSize / $ratio, 0, 0, $originalWidth, $originalHeight],

            'crop' => $ratio > 1
                ? [$newSize, $newSize, ($originalWidth - $originalHeight) / 2, 0, $originalHeight, $originalHeight]
                : [$newSize, $newSize, 0, ($originalHeight - $originalWidth) / 2, $originalWidth, $originalWidth],

            default => null,
        };

        if ($dimensions === null) {
            return null;
        }

        return array_map(
            static fn(float|int $value): int => (int) round($value),
            $dimensions
        );
    }

    /**
     * Create the destination image canvas.
     */
    private function createCanvas(int $width, int $height, string $type): GdImage {
        $canvas = imagecreatetruecolor($width, $height);

        if (in_array($type, ['webp', 'png'], true)) {
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);

            $transparent = imagecolorallocatealpha($canvas, 255, 255, 255, 127);

            imagefilledrectangle($canvas, 0, 0, $width, $height, $transparent);
        }

        return $canvas;
    }

    /**
     * Create and save the resized image.
     */
    private function create(
        GdImage $originalImage,
        GdImage $canvas,
        string $path,
        string $type,
        array $dimensions,
        int $quality,
    ): bool {
        [$newWidth, $newHeight, $x, $y, $originalWidth, $originalHeight] = $dimensions;

        $resampled = imagecopyresampled(
            $canvas,
            $originalImage,
            0,
            0,
            $x,
            $y,
            $newWidth,
            $newHeight,
            $originalWidth,
            $originalHeight,
        );

        if (!$resampled) {
            return false;
        }

        $fullPath = Storage::disk('public')->path($path);

        return match ($type) {
            'webp' => imagewebp($canvas, $fullPath, $quality),
            'jpg' => imagejpeg($canvas, $fullPath, $quality),
            'png' => imagepng($canvas, $fullPath),
            default => false,
        };
    }
}
