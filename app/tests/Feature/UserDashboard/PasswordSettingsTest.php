<?php

namespace Tests\Feature\UserDashboard;

use App\Livewire\UserDashboard\Settings\Password;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class PasswordSettingsTest extends TestCase
{
    use DatabaseTransactions;

    // The factory hashes this value for every user it creates.
    private const FACTORY_PASSWORD = 'password';

    private function randomPassword(): string
    {
        return fake()->regexify('[A-Za-z0-9]{16}');
    }

    public function test_it_updates_the_password_and_resets_the_fields(): void
    {
        $user = User::factory()->create();
        $new = $this->randomPassword();

        Livewire::actingAs($user)
            ->test(Password::class)
            ->set('current_password', self::FACTORY_PASSWORD)
            ->set('new_password', $new)
            ->set('new_password_confirmation', $new)
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('current_password', '')
            ->assertSet('new_password', '')
            ->assertSet('new_password_confirmation', '');

        $this->assertTrue(Hash::check($new, $user->fresh()->password));
    }

    public function test_it_rejects_a_wrong_current_password(): void
    {
        $user = User::factory()->create();
        $oldHash = $user->password;
        $new = $this->randomPassword();

        Livewire::actingAs($user)
            ->test(Password::class)
            ->set('current_password', $this->randomPassword())
            ->set('new_password', $new)
            ->set('new_password_confirmation', $new)
            ->call('save')
            ->assertHasErrors(['current_password']);

        $this->assertSame($oldHash, $user->fresh()->password);
    }

    public function test_it_requires_a_password_of_at_least_8_characters(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Password::class)
            ->set('current_password', self::FACTORY_PASSWORD)
            ->set('new_password', 'short')
            ->set('new_password_confirmation', 'short')
            ->call('save')
            ->assertHasErrors(['new_password' => 'min']);

        $this->assertTrue(Hash::check(self::FACTORY_PASSWORD, $user->fresh()->password));
    }

    public function test_it_requires_the_confirmation_to_match(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Password::class)
            ->set('current_password', self::FACTORY_PASSWORD)
            ->set('new_password', $this->randomPassword())
            ->set('new_password_confirmation', $this->randomPassword())
            ->call('save')
            ->assertHasErrors(['new_password' => 'confirmed']);

        $this->assertTrue(Hash::check(self::FACTORY_PASSWORD, $user->fresh()->password));
    }
}
