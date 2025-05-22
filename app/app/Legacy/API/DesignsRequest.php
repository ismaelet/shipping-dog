<?php

/* OLD
namespace API;

class DesignsRequest {

	// GET request
	static function get($requestContent, $id = false) {
		if ($id)
			$response['content'] = API_GET('design', $requestContent, $id);
		else {
			$queryFields = [];
			$searchFields = explode(',', $requestContent['searchFields']);

			// query fields
			foreach ($searchFields as $searchField) {
				switch ($searchField) {
					case 'id':
						$queryFields[] = 'ID = ?';
						break;
					case 'name':
						$queryFields[] = $searchField . " LIKE CONCAT('%',?,'%')";
						break;
				}
			}

			$ids = API_GET_ids($requestContent, $queryFields, 'designs', 'id');
			$response['content'] = API_GET('Design', $requestContent, false, $ids);
		}

		return $response;
	}
}
*/