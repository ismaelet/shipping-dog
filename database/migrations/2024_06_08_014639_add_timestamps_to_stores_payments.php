<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		Schema::table('stores_payments', function (Blueprint $table) {
			$table->timestamps();
		});

		DB::table('stores_payments')->update(['created_at' => DB::raw('time')]);
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::table('stores_payments', function (Blueprint $table) {
			$table->dropTimestamps();
		});
	}
};
