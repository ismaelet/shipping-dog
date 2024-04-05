<?php

// get designs
$designs = DBOld::select('ID, sort FROM designs WHERE userID = ? AND status > 0 AND custom = 0 ORDER BY sort ASC, ID DESC', [auth()->id()]);
$customProducts = DBOld::select('ID FROM items WHERE userID = ? AND deleted = 0 AND (desID = 0 OR desID IN (SELECT ID FROM designs WHERE custom = 1)) ORDER BY sort ASC, ID DESC', [auth()->id()]);

$designCount = $designs->num_rows;
$customProductsCount = $customProducts->num_rows;
$totalCount = $designCount + $customProductsCount;

if (isset($_POST['sort'])) {
	if (isset($_POST['id'])) {
		foreach ($_POST['id'] as $i => $desID) {
			$pos = $_POST['pos'][$i];

			DBOld::update('designs SET sort=? WHERE ID = ?', [$pos, $desID]);
			DBOld::update('items SET sort=? WHERE desID = ?', [$pos, $desID]);
		}
	}

	if (isset($_POST['product-id'])) {
		foreach ($_POST['product-id'] as $i => $productId) {
			$productOrder = $_POST['product-order'][$i];

			DBOld::update('items SET sort=? WHERE ID = ?', [$productOrder, $productId]);
		}
	}

	redirect_old();
}
