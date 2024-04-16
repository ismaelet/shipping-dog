<?php

use App\Models\Ecommerce\Product\ProductType;

$images = [];
$type_id = $_GET['type_id'];
$colorId = $_GET['colorId'];
$position_id = $_GET['position_id'] ?? null;

$type = ProductType::find($type_id);

$images['clothing'] = $type->image($colorId, $position_id);
$images['clothing-full-url'] = public_asset($images['clothing']);
$images['details'] = $type->details($colorId, $position_id);
$images['details-full-url'] = $images['details'] ? public_asset($images['details']) : null;

echo json_encode($images);
