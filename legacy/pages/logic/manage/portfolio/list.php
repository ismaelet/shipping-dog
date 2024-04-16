<?php

// get designs
$designs = DBOld::select('id, sort FROM designs WHERE user_id = ? AND status > 0 AND custom = 0 ORDER BY sort ASC, id DESC', [auth()->id()]);
$customProducts = DBOld::select('id FROM items WHERE user_id = ? AND deleted = 0 AND (design_id = 0 OR design_id IN (SELECT id FROM designs WHERE custom = 1)) ORDER BY sort ASC, id DESC', [auth()->id()]);

$designCount = $designs->num_rows;
$customProductsCount = $customProducts->num_rows;
$totalCount = $designCount + $customProductsCount;

if (isset($_POST['sort'])) {
	if (isset($_POST['id'])) {
		foreach ($_POST['id'] as $i => $design_id) {
			$pos = $_POST['pos'][$i];

			DBOld::update('designs SET sort=? WHERE id = ?', [$pos, $design_id]);
			DBOld::update('items SET sort=? WHERE design_id = ?', [$pos, $design_id]);
		}
	}

	if (isset($_POST['product-id'])) {
		foreach ($_POST['product-id'] as $i => $productId) {
			$productOrder = $_POST['product-order'][$i];

			DBOld::update('items SET sort=? WHERE id = ?', [$productOrder, $productId]);
		}
	}

	redirect_old();
}
