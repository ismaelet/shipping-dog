<?php

use App\Models\Ecommerce\Design;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		Schema::table('designs', function (Blueprint $table) {
			$table->unsignedBigInteger('default_color_id')->after('color')
				->default(1);
		});

		Schema::table('designs', function (Blueprint $table) {
			$table->index('default_color_id');
		});

		Design::rightJoin('products', 'designs.id', '=', 'products.design_id')
			->where('products.type_id', 1)
			->update([
				'designs.default_color_id' => DB::raw('products.color_id'),
			]);
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::table('designs', function (Blueprint $table) {
			$table->dropColumn('default_color_id');
		});

		Schema::table('designs', function (Blueprint $table) {
			$table->dropIndex('designs_default_color_id_index');
		});
	}
};
