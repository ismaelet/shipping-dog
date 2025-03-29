<?php

namespace Legacy\System\Classes;

class ApiController {

	public function processRequest($requestEndpoint, $requestMethod, $requestContent) {

		// setup request
		$requestClass = ucfirst($requestEndpoint);
		$classMethod = 'API_' . $requestMethod;

		if (method_exists($requestClass, $classMethod)) {
			// create the related object
			if (in_array($requestMethod, ['GET', 'PATCH'])) {
				$entry = new $requestClass($requestContent['id']);
			}

			// submit the response
			switch ($requestMethod) {
				case 'GET':
				case 'PATCH':
					$response['header'] = 'HTTP/1.1 200 OK';
					$apiResponse = $entry->$classMethod($requestContent);
					break;
				case 'POST':
				case 'PUT':
				case 'DELETE':
					$response['header'] = 'HTTP/1.1 201 Created';
					$apiResponse = $requestClass::$classMethod($requestContent);
					break;
			}

			if (isset($apiResponse['header'])) {
				$response['header'] = $apiResponse['header'];
			}

			if (isset($apiResponse['error'])) {
				$response['content']['error'] = $apiResponse['error'];
			} else {
				$response['content'] = $apiResponse;
			}
		} else {
			$response['header'] = 'HTTP/1.1 400 Bad Request';
			$response['content'] = 'Error: ' . $requestClass . '::' . $classMethod . '() method doesn\'t exist';
		}

		if (isset($response['header'])) header($response['header']);
		if (isset($response['content'])) echo json_encode($response['content']);
	}
}
