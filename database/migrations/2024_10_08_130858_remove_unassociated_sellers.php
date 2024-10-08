<?php

use App\Models\Ecommerce\Seller;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		Seller::leftJoin('users', 'stores.user_id', '=', 'users.id')
			->where('users.id', null)
			->with('designs', 'followers', 'prices', 'payment_request', 'logoGroups')
			->get()
			->each(function ($seller) {
				$seller->delete();
			});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		//
	}
};
