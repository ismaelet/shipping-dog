<?php

$response = [];
$table = 'designs';
$showField = 'fullName';
$search = $_GET['search'] ?? '';
$search = trim($search);

if ($search) {
	$results = \Legacy\System\Classes\LegacyDB::select("ID, CONCAT(ID, ' - ', name) AS " . $showField . " FROM " . $table . " WHERE id = ? OR name LIKE CONCAT('%',?,'%') ORDER BY " . $showField . " ASC LIMIT 10", [$search, $search]);

	while ($result = $results->fetch_assoc()) {
		$response[] = [
			'value-field' => $result['id'],
			'show-field' => $result[$showField],
		];
	}
}

echo json_encode($response);
