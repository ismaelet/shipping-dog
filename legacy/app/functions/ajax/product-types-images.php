<?php

use Ecommerce\Product\Type;

$images = [];
$typeId = $_GET['typeId'];
$colorId = $_GET['colorId'];
$positionId = $_GET['positionId'] ?? null;

$type = new Type($typeId);

$images['clothing'] = $type->getImage($colorId, $positionId);
$images['clothing-full-url'] = client_url($images['clothing']);
$images['details'] = $type->getDetailsImage($colorId, $positionId);
$images['details-full-url'] = $images['details'] ? client_url($images['details']) : null;

echo json_encode($images);
