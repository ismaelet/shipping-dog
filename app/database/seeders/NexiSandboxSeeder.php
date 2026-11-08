<?php

namespace Database\Seeders;

use App\Models\Ecommerce\Order;
use App\Models\User;
use App\Services\Payments\NexiSandbox;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class NexiSandboxSeeder extends Seeder
{
    public function run(): void
    {
        app(NexiSandbox::class)->assertIsolated();
        $password = Str::random(24);
        [$customer, $guest, $owned] = DB::transaction(function () use ($password) {
            DB::table('payment_methods')->updateOrInsert(['id' => 2], [
                'tag' => 'nexi', 'name' => 'Carta sandbox', 'desc' => 'Solo test Nexi', 'active' => 1,
            ]);
            DB::table('sys_shipping')->updateOrInsert(['id' => 2], [
                'tag' => 'std', 'shortName' => 'Sandbox', 'name' => 'Spedizione sandbox',
                'desc' => 'Nessuna spedizione reale', 'days_min' => 1, 'days_max' => 6,
            ]);
            $customer = User::firstOrCreate(['email' => 'nexi@example.test'], [
                'first_name' => 'Nexi', 'last_name' => 'Sandbox', 'password' => $password,
            ]);
            $customer->update(['password' => $password]);
            $orders = [];
            foreach ([null, $customer->id] as $userId) {
                $order = Order::create([
                    'user_id' => $userId, 'price' => '12.34', 'quantity' => 1,
                    'payment_method_id' => 2, 'shipping_method_id' => 2, 'status' => 0, 'step' => 4,
                ]);
                DB::table('addresses')->insert([
                    'order_id' => $order->id, 'full_name' => 'Sandbox Buyer',
                    'address' => 'Via Test 1', 'zip' => '00100', 'city' => 'Roma',
                    'email' => 'buyer@example.test',
                ]);
                DB::table('order_items')->insert([
                    'order_id' => $order->id, 'quantity' => 1,
                    'unit_amount_to_pay' => '12.34', 'total_amount_to_pay' => '12.34',
                ]);
                $orders[] = $order;
            }

            return [$customer, ...$orders];
        });
        $this->command?->info('New synthetic EUR 12.34 orders created; existing attempts were preserved.');
        $this->command?->line('Guest (signed-out browser; valid for 2 hours): '.URL::temporarySignedRoute(
            'nexi.sandbox.guest', now()->addHours(2), ['order' => $guest->id]
        ));
        $this->command?->line('Customer login: '.$customer->email.' / '.$password);
        $this->command?->line('After login, open: '.route('nexi.orders.create', $owned));
    }
}
