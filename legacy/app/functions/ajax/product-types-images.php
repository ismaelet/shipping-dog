<?php

use App\Models\Ecommerce\Product\ProductType;

$images = [];
$typeId = $_GET['typeId'];
$colorId = $_GET['colorId'];
$positionId = $_GET['positionId'] ?? null;

$type = ProductType::find($typeId);

$images['clothing'] = $type->image($colorId, $positionId);
$images['clothing-full-url'] = public_asset($images['clothing']);
$images['details'] = $type->details($colorId, $positionId);
$images['details-full-url'] = $images['details'] ? public_asset($images['details']) : null;

echo json_encode($images);
