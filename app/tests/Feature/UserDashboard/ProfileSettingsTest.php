<?php

namespace Tests\Feature\UserDashboard;

use App\Livewire\UserDashboard\Settings\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileSettingsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_it_is_prefilled_with_the_user_data(): void
    {
        $user = User::factory()->create(['first_name' => 'Mario', 'last_name' => 'Rossi']);

        Livewire::actingAs($user)
            ->test(Profile::class)
            ->assertSet('firstName', 'Mario')
            ->assertSet('lastName', 'Rossi')
            ->assertSet('email', $user->email);
    }

    public function test_it_updates_the_profile_and_dispatches_the_name_event(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Profile::class)
            ->set('firstName', 'Luigi')
            ->set('lastName', 'Verdi')
            ->set('email', 'luigi@example.com')
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('userNameUpdated');

        $user->refresh();

        $this->assertSame('Luigi', $user->first_name);
        $this->assertSame('Verdi', $user->last_name);
        $this->assertSame('luigi@example.com', $user->email);
    }

    public function test_it_requires_a_valid_email(): void
    {
        Livewire::actingAs(User::factory()->create())
            ->test(Profile::class)
            ->set('email', '')
            ->call('save')
            ->assertHasErrors(['email' => 'required'])
            ->set('email', 'not-an-email')
            ->call('save')
            ->assertHasErrors(['email' => 'email']);
    }

    public function test_it_rejects_an_email_used_by_another_user(): void
    {
        $other = User::factory()->create();
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Profile::class)
            ->set('email', $other->email)
            ->call('save')
            ->assertHasErrors(['email'])
            ->assertNotDispatched('userNameUpdated');

        $this->assertNotSame($other->email, $user->fresh()->email);
    }

    public function test_it_rejects_names_that_are_too_long(): void
    {
        Livewire::actingAs(User::factory()->create())
            ->test(Profile::class)
            ->set('firstName', str_repeat('a', 256))
            ->call('save')
            ->assertHasErrors(['firstName' => 'max']);
    }
}
