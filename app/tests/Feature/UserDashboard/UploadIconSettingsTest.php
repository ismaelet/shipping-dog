<?php

namespace Tests\Feature\UserDashboard;

use App\Livewire\UploadImage;
use App\Livewire\UserDashboard\Settings\UploadIcon;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class UploadIconSettingsTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_icon_upload_stores_a_webp_and_dispatches_the_event(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(UploadIcon::class)
            ->set('image', UploadedFile::fake()->image('icon.png', 400, 400))
            ->assertHasNoErrors()
            ->assertDispatched('userIconUpdated');

        Storage::disk('public')->assertExists('users/icons/'.$user->id.'.webp');
    }

    public function test_icon_upload_rejects_non_images(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(UploadIcon::class)
            ->set('image', UploadedFile::fake()->create('doc.pdf', 10, 'application/pdf'))
            ->assertHasErrors(['image' => 'image'])
            ->assertNotDispatched('userIconUpdated');

        Storage::disk('public')->assertMissing('users/icons/'.$user->id.'.webp');
    }

    public function test_icon_upload_rejects_files_over_the_limit(): void
    {
        Livewire::actingAs(User::factory()->create())
            ->test(UploadIcon::class)
            ->set('image', UploadedFile::fake()->image('huge.png')->size(10241))
            ->assertHasErrors(['image' => 'max']);
    }

    public function test_the_settings_view_renders_both_image_uploaders(): void
    {
        Livewire::actingAs(User::factory()->create())
            ->test(UploadIcon::class)
            ->assertSee('Immagine profilo')
            ->assertSee('Immagine di copertina')
            ->assertSeeLivewire(UploadImage::class);
    }

    public function test_upload_image_stores_the_image_and_dispatches_the_configured_event(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(UploadImage::class, [
                'url' => $user->coverPath(),
                'size' => 1600,
                'quality' => 90,
                'aspect' => 'cover',
                'event' => 'userCoverUpdated',
            ])
            ->set('image', UploadedFile::fake()->image('cover.jpg', 2000, 800))
            ->assertHasNoErrors()
            ->assertDispatched('userCoverUpdated');

        Storage::disk('public')->assertExists($user->coverPath());
    }

    public function test_upload_image_reports_files_over_32mb(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(UploadImage::class, ['url' => $user->coverPath(), 'size' => 1600, 'quality' => 90])
            ->set('image', UploadedFile::fake()->image('big.jpg')->size(32769))
            ->assertHasErrors(['image']);

        // Rejected while uploading the temporary file: the browser then fires livewire-upload-error
        Storage::disk('public')->assertMissing($user->coverPath());
    }

    public function test_upload_image_reports_a_processing_failure(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(UploadImage::class, ['url' => $user->coverPath(), 'size' => 0, 'quality' => 90])
            ->set('image', UploadedFile::fake()->image('cover.jpg'))
            ->assertHasErrors(['image'])
            ->assertDispatched('upload-image-error');
    }
}
