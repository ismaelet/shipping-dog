<?php

$search = $_GET['search'] ?? '';

$searchN = 0;
$max = 20;

if (strlen($search) > 2) {

	$search = trim(strtolower($search));

	//get design results
	$res_des = DBOld::select(

		"id FROM designs WHERE (

			name LIKE CONCAT('%',?,'%') OR

			ID IN (SELECT design_id FROM designs_tags WHERE tags LIKE CONCAT('%',?,'%')) OR 

			user_id IN (SELECT user_id FROM stores WHERE name LIKE CONCAT('%',?,'%'))
		)
		
		AND status>0

		AND user_id IN (SELECT user_id FROM stores WHERE status = 1)

		ORDER BY sales DESC, user_id ASC, id DESC LIMIT " . $max,

		[$search, $search, $search]
	);

	$desN = $res_des->num_rows;

	//get seller results
	$res_sell = DBOld::select("user_id FROM stores WHERE name LIKE CONCAT('%',?,'%') AND status = 1 ORDER BY user_id DESC LIMIT " . $max, [$search]);

	$sellN = $res_sell->num_rows;

	//get total number of results		
	$searchN = $desN + $sellN;
}
