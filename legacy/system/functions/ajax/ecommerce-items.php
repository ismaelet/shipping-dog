<?php

$response = [];

$productId = $_GET['id'];
$product = new \Legacy\Classes\Ecommerce\Product($productId);

$scope = explode(',', $_GET['scope']);
$parameters = isset($_GET['parameters']) ? explode(',', $_GET['parameters']) : null;

if ($parameters) foreach ($parameters as $parameter) {
	$parameter = explode(':', $parameter);
	$product->{$parameter[0]} = $parameter[1];
}

foreach ($scope as $field) {
	$response[$field] = $product->{$field};
}

echo json_encode($response);
