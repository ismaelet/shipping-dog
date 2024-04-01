<?php

/* OLD
namespace API;

class SellersRequest {

	//GET request
	static function get($requestContent, $id = false) {
		if ($id)
			$response['content'] = API_GET('Seller', $requestContent, $id);
		else {
			$queryFields = [];
			$searchFields = explode(',', $requestContent['searchFields']);

			// query fields
			foreach ($searchFields as $searchField) {
				switch ($searchField) {
					case 'id':
						$queryFields[] = 'userID = ?';
						break;
					case 'name':
						$queryFields[] = $searchField . " LIKE CONCAT('%',?,'%')";
						break;
				}
			}

			$ids = API_GET_ids($requestContent, $queryFields, 'stores', 'userID');
			$response['content'] = API_GET('Seller', $requestContent, false, $ids);
		}

		return $response;
	}
}
*/