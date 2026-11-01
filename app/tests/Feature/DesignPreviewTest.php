<?php

namespace Tests\Feature;

use App\Models\Ecommerce\Design;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DesignPreviewTest extends TestCase
{
    public static function dimensions(): array
    {
        return [[200, 100], [100, 200], [100, 100]];
    }

    #[DataProvider('dimensions')]
    public function test_image_generates_centered_previews_on_the_design_background(int $width, int $height): void
    {
        Storage::fake('public');
        $disk = Storage::disk('public');
        $disk->makeDirectory('designs');
        $source = imagecreatetruecolor($width, $height);
        imagealphablending($source, false);
        imagesavealpha($source, true);
        imagefill($source, 0, 0, imagecolorallocatealpha($source, 0, 0, 0, 127));
        imagefilledrectangle($source, $width / 4, $height / 4, $width * 3 / 4, $height * 3 / 4, imagecolorallocate($source, 220, 30, 40));
        imagepng($source, $disk->path('designs/123.png'));
        imagedestroy($source);

        $design = new Design(['id' => 123, 'color' => '#204080']);

        $this->assertStringContainsString('storage/designs/previews/123_m.webp', $design->image());
        $this->assertStringContainsString('storage/designs/previews/123.webp', $design->image(1024));

        foreach (['123.webp' => 1024, '123_m.webp' => 400] as $filename => $size) {
            $path = $disk->path('designs/previews/'.$filename);
            $this->assertSame([$size, $size, IMAGETYPE_WEBP], array_slice(getimagesize($path), 0, 3));
            $preview = imagecreatefromwebp($path);

            try {
                foreach ([[5, 5, [32, 64, 128]], [$size / 2, $size / 2, [220, 30, 40]]] as [$x, $y, $expected]) {
                    $actual = imagecolorsforindex($preview, imagecolorat($preview, $x, $y));
                    foreach (['red', 'green', 'blue'] as $index => $channel) {
                        $this->assertEqualsWithDelta($expected[$index], $actual[$channel], 8);
                    }
                }

                // These points distinguish fitted landscape/portrait artwork from stretched artwork.
                if ($width !== $height) {
                    $x = $width > $height ? $size / 2 : $size * 0.3;
                    $y = $width > $height ? $size * 0.3 : $size / 2;
                    $color = imagecolorsforindex($preview, imagecolorat($preview, (int) $x, (int) $y));
                    $this->assertEqualsWithDelta(128, $color['blue'], 8);
                }
            } finally {
                imagedestroy($preview);
            }
        }
    }

    public function test_cached_preview_is_not_regenerated(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('designs/previews/123_m.webp', 'cached');
        $design = new Design(['id' => 123]);

        $this->assertStringContainsString('storage/designs/previews/123_m.webp', $design->image());
        $this->assertSame('cached', Storage::disk('public')->get('designs/previews/123_m.webp'));
        Storage::disk('public')->assertMissing('designs/previews/123.webp');
    }
}
