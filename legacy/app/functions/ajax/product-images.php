<?php

use Legacy\Classes\Ecommerce\Product;

$response = [];
$productId = $_GET['id'];
$color_id = $_GET['color_id'];
$position_id = $_GET['position_id'];

$product = (new Product($productId))
	->setColorId($color_id)
	->setPositionId($position_id);

$response['1024-fullUrl'] = client_url($product->fullImage);

echo json_encode($response);
