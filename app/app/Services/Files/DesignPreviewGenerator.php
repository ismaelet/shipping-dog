<?php

namespace App\Services\Files;

use App\Models\Ecommerce\Design;
use Illuminate\Support\Facades\Storage;

class DesignPreviewGenerator {

    public function generate(Design $design): void {
        $disk = Storage::disk('public');
        $sourcePath = 'designs/' . $design->id . '.png';

        if (!$disk->exists($sourcePath)) {
            return;
        }

        $source = imagecreatefrompng($disk->path($sourcePath));

        if ($source === false) {
            return;
        }

        $preview = imagecreatetruecolor(1024, 1024);

        try {
            [$red, $green, $blue] = sscanf($design->color ?? '#ffffff', '#%02x%02x%02x');
            $background = imagecolorallocate($preview, $red, $green, $blue);
            imagefill($preview, 0, 0, $background);

            $width = imagesx($source);
            $height = imagesy($source);
            $scale = 1024 / max($width, $height);
            $targetWidth = max(1, (int) round($width * $scale));
            $targetHeight = max(1, (int) round($height * $scale));

            imagecopyresampled(
                $preview, $source,
                (int) ((1024 - $targetWidth) / 2), (int) ((1024 - $targetHeight) / 2),
                0, 0, $targetWidth, $targetHeight, $width, $height,
            );

            $disk->makeDirectory('designs/previews');
            $previewPath = 'designs/previews/' . $design->id . '.webp';

            if (!imagewebp($preview, $disk->path($previewPath), 90)) {
                return;
            }

            // Use the encoded HD preview as the source, matching the legacy output.
            $encodedPreview = imagecreatefromwebp($disk->path($previewPath));

            if ($encodedPreview === false) {
                return;
            }

            $thumbnail = imagecreatetruecolor(400, 400);

            try {
                imagecopyresampled($thumbnail, $encodedPreview, 0, 0, 0, 0, 400, 400, 1024, 1024);
                imagewebp($thumbnail, $disk->path('designs/previews/' . $design->id . '_m.webp'), 80);
            } finally {
                imagedestroy($thumbnail);
                imagedestroy($encodedPreview);
            }
        } finally {
            imagedestroy($preview);
            imagedestroy($source);
        }
    }
}
