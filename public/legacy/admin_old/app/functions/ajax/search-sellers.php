<?php

$response = [];
$table = 'stores';
$showField = 'name';
$search = $_GET['search'] ?? '';
$search = strtolower(trim($search));

if ($search) {
	$results = DB::select("userID, " . $showField . " FROM " . $table . " WHERE userID = ? OR LOWER(" . $showField . ") LIKE CONCAT('%',?,'%') ORDER BY " . $showField . " ASC, userID DESC LIMIT 10", [$search, $search]);

	while ($result = $results->fetch_assoc()) {
		$response[] = [
			'value-field' => $result['userID'],
			'show-field' => $result['userID'] . ' - ' . $result[$showField],
		];
	}
}

echo json_encode($response);
