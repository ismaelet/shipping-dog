<?php

$response = [];
$table = 'designs';
$showField = 'fullName';
$search = $_GET['search'] ?? '';
$search = trim($search);

if ($search) {
	$results = DB::select("ID, CONCAT(ID, ' - ', name) AS " . $showField . " FROM " . $table . " WHERE ID = ? OR name LIKE CONCAT('%',?,'%') ORDER BY " . $showField . " ASC LIMIT 10", [$search, $search]);

	while ($result = $results->fetch_assoc()) {
		$response[] = [
			'value-field' => $result['ID'],
			'show-field' => $result[$showField],
		];
	}
}

echo json_encode($response);
