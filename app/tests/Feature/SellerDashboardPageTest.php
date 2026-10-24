<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SellerDashboardPageTest extends TestCase {
    use DatabaseTransactions;

    public function test_seller_dashboard_displays_the_designed_sections_and_preserves_contact_fields(): void {
        $user = User::factory()->create();
        $seller = $user->seller()->create([
            'name' => 'Studio Test',
            'sort' => 1,
            'status' => false,
        ]);

        $this->actingAs($user)
            ->get(route('seller-dashboard'))
            ->assertOk()
            ->assertViewIs('seller-dashboard.index')
            ->assertSee(route('seller.show', $seller), false);
    }

    public function test_portfolio_and_design_upload_routes_render_the_same_page_with_the_selected_tab(): void {
        $user = User::factory()->create();
        $user->seller()->create([
            'name' => 'Studio Test',
            'sort' => 1,
            'status' => false,
        ]);

        $this->actingAs($user)
            ->get(route('seller-dashboard.portfolio'))
            ->assertOk()
            ->assertViewIs('seller-dashboard.portfolio')
            ->assertViewHas('activeTab', false)
            ->assertSee('Le tue creazioni')
            ->assertSee('Carica una grafica');

        $this->get(route('seller-dashboard.portfolio.add'))
            ->assertOk()
            ->assertViewIs('seller-dashboard.portfolio')
        ->assertViewHas('activeTab', true)
        ->assertSee('Requisiti del file')
            ->assertSee('Carica una nuova grafica');
    }
}
