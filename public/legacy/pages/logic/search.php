<?php

$search = $_GET['search'] ?? '';

$searchN = 0;
$max = 20;

if (strlen($search) > 2) {

	$search = trim(strtolower($search));

	//get design results
	$res_des = DB::select(

		"ID FROM designs WHERE (

			name LIKE CONCAT('%',?,'%') OR

			ID IN (SELECT desID FROM designs_tags WHERE tags LIKE CONCAT('%',?,'%')) OR 

			userID IN (SELECT userID FROM stores WHERE name LIKE CONCAT('%',?,'%'))
		)
		
		AND status>0

		AND userID IN (SELECT userID FROM stores WHERE status = 1)

		ORDER BY sales DESC, userID ASC, ID DESC LIMIT " . $max,

		[$search, $search, $search]
	);

	$desN = $res_des->num_rows;

	//get seller results
	$res_sell = DB::select("userID FROM stores WHERE name LIKE CONCAT('%',?,'%') AND status = 1 ORDER BY userID DESC LIMIT " . $max, [$search]);

	$sellN = $res_sell->num_rows;

	//get total number of results		
	$searchN = $desN + $sellN;
}
