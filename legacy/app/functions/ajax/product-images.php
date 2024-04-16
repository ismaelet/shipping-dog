<?php

use Ecommerce\Product;

$response = [];
$productId = $_GET['id'];
$colorId = $_GET['colorId'];
$position_id = $_GET['position_id'];

$product = (new Product($productId))
	->setColorId($colorId)
	->setPositionId($position_id);

$response['1024-fullUrl'] = client_url($product->fullImage);

echo json_encode($response);
