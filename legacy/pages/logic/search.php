<?php

use App\Models\Ecommerce\Design;

$search = $_GET['search'] ?? '';

$searchN = 0;
$max = 20;

if (strlen($search) > 2) {

	$search = trim(strtolower($search));

	//get design results
	$designs = Design::where('name', 'like', "%$search%")
		->orWhereHas('tags', function ($query) use ($search) {
			$query->where('tags', 'like', "%$search%");
		})
		->orWhereHas('seller', function ($query) use ($search) {
			$query->where('name', 'like', "%$search%")
				->where('status', 1);
		})
		->where('status', '>', 0)
		->orderBy('sales', 'desc')
		->orderBy('id', 'desc')
		->limit($max)
		->get();

	$desN = $designs->count();

	//get seller results
	$res_sell = \Legacy\System\Classes\LegacyDB::select("user_id FROM stores WHERE name LIKE CONCAT('%',?,'%') AND status = 1 ORDER BY user_id DESC LIMIT " . $max, [$search]);

	$sellN = $res_sell->num_rows;

	//get total number of results		
	$searchN = $desN + $sellN;
}
