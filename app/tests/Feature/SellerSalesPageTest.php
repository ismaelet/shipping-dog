<?php

namespace Tests\Feature;

use App\Livewire\Seller\Sales;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class SellerSalesPageTest extends TestCase
{
    use DatabaseTransactions;

    public function test_sales_page_renders_the_summary_and_view_tabs_for_both_sort_modes(): void
    {
        $user = User::factory()->create();
        $user->seller()->create([
            'name' => 'Studio Test',
            'sort' => 1,
            'status' => false,
        ]);

        foreach (['time', 'designs'] as $sort) {
            $this->actingAs($user)
                ->get(route('seller-dashboard.sales', ['sort' => $sort]))
                ->assertOk()
                ->assertSee('Lista vendite')
                ->assertSee('Guadagno totale')
                ->assertSee('Cronologia')
                ->assertSee('Ancora non hai realizzato nessuna vendita');
        }
    }

    public function test_view_can_be_switched_without_a_page_reload(): void
    {
        $user = User::factory()->create();
        $seller = $user->seller()->create([
            'name' => 'Studio Test',
            'sort' => 1,
            'status' => false,
        ]);

        Livewire::actingAs($user)
            ->test(Sales::class, ['seller' => $seller])
            ->assertSet('sort', 'time')
            ->call('showSort', 'designs')
            ->assertSet('sort', 'designs')
            ->call('showSort', 'invalid')
            ->assertSet('sort', 'time');
    }
}
