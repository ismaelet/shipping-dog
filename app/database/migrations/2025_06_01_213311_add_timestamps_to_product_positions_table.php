<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		Schema::table('product_positions', function (Blueprint $table) {
			$table->timestamps();
			$table->string('tag')->change();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::table('product_positions', function (Blueprint $table) {
			$table->dropTimestamps();
		});
	}
};
