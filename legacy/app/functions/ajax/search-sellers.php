<?php

$response = [];
$table = 'stores';
$showField = 'name';
$search = $_GET['search'] ?? '';
$search = strtolower(trim($search));

if ($search) {
	$results = DBOld::select("user_id, " . $showField . " FROM " . $table . " WHERE user_id = ? OR LOWER(" . $showField . ") LIKE CONCAT('%',?,'%') ORDER BY " . $showField . " ASC, user_id DESC LIMIT 10", [$search, $search]);

	while ($result = $results->fetch_assoc()) {
		$response[] = [
			'value-field' => $result['user_id'],
			'show-field' => $result['user_id'] . ' - ' . $result[$showField],
		];
	}
}

echo json_encode($response);
