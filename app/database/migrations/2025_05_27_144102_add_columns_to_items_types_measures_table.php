<?php

use App\Models\Ecommerce\Product\Type\Measure;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		Schema::table('items_types_measures', function (Blueprint $table) {
			$table->float('width')->after('val');
			$table->float('height')->after('width');
		});

		$materialMeasures = Measure::where('size', 'mat')->get();

		foreach ($materialMeasures as $measure) {
			$measure->delete();
		}

		$measures = Measure::all();

		foreach ($measures as $measure) {
			[$width, $height] = explode(',', $measure->val);

			$measure->width = (float) $width;
			$measure->height = (float) $height;
			$measure->save();
		}
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::table('items_types_measures', function (Blueprint $table) {
			$table->dropColumn(['width', 'height']);
		});
	}
};
