<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserDashboardRoutesTest extends TestCase
{
    use DatabaseTransactions;

    public function test_dashboard_routes_redirect_guests_to_login(): void
    {
        foreach ([
            '/user-dashboard',
            '/user-dashboard/settings',
            '/user-dashboard/orders',
            '/user-dashboard/orders/download',
            '/user-dashboard/wishlist',
        ] as $uri) {
            $this->get($uri)->assertRedirect(route('login'));
        }

        $this->delete('/user')->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_open_each_dashboard_page(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->get('/user-dashboard')
            ->assertOk()
            ->assertViewIs('user-dashboard.recap')
            ->assertSee('La tua attività');

        $this->get('/user-dashboard/settings')
            ->assertOk()
            ->assertViewIs('user-dashboard.settings')
            ->assertSee('Il tuo profilo');

        $this->get('/user-dashboard/orders')
            ->assertOk()
            ->assertViewIs('user-dashboard.orders')
            ->assertSee('Nessun ordine effettuato');

        $this->get('/user-dashboard/wishlist')
            ->assertOk()
            ->assertViewIs('user-dashboard.wishlist')
            ->assertSee('Nessuna grafica nella wishlist');
    }

    public function test_authenticated_user_can_download_their_order_history_as_a_pdf(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->get('/user-dashboard/orders/download');

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf')
            ->assertHeader('Content-Disposition', 'attachment; filename="storico-ordini.pdf"');

        $this->assertStringStartsWith('%PDF-', $response->getContent());
    }

    public function test_authenticated_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->delete('/user')
            ->assertRedirect(route('login'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted('users', ['id' => $user->id]);
        $this->assertGuest();
    }
}
