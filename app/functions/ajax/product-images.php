<?php

use Ecommerce\Product;

$response = [];
$productId = $_GET['id'];
$colorId = $_GET['colorId'];
$positionId = $_GET['positionId'];

$product = (new Product($productId))
	->setColorId($colorId)
	->setPositionId($positionId);

$response['1024-fullUrl'] = client_url($product->fullImage);

echo json_encode($response);
