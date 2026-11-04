<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $users = User::whereHas('seller')
            ->get();

        $users->each(function ($user) {
            $seller = DB::table('sellers')
                ->where('user_id', $user->id)
                ->first();

            if ($seller && $seller->name) {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['name' => $seller->name]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
