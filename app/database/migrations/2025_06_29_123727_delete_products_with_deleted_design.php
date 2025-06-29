<?php

use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		// Delete products with deleted designs
		Design::where('status', false)->delete();

		Product::whereHas('design_with_trashed', function ($query) {
			$query->whereNotNull('deleted_at');
		})->delete();
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		//
	}
};
