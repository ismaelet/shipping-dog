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
}
