<?php

/*
* Page configuration
*/
$pageConfig['add'] = false;
$rowLinks = "add?id={{id}}";
$queryConfig['index'] = 'ID';
$queryConfig['table'] = 'items';
$queryConfig['where-search'] = "name LIKE CONCAT('%',?,'%') OR ID = ? OR userID IN (SELECT userID FROM stores WHERE name LIKE CONCAT('%',?,'%'))";

// select fields
$fields = [
	['title' => 'ID', 'name' => 'ID'],
	['title' => 'Nome', 'name' => 'name', 'size' => 1],
	['title' => 'Artista', 'type' => 'find', 'name' => 'userID', 'find' => 'name FROM stores WHERE userID = ?', 'size' => 1],
	['title' => 'Tipo', 'type' => 'find', 'name' => 'typeID', 'find' => 'name FROM items_types WHERE ID = ?', 'size' => 1],
	// ['title' => 'Vendite', 'name' => 'sales', 'size' => 'm', 'parse' => 'int'],
	// ['title' => 'Vendite rec.', 'name' => 'rsales', 'size' => 'm', 'parse' => 'int'],
	['title' => 'Data', 'name' => 'time',],
	['title' => 'Attivo', 'name' => 'status', 'type' => 'boolean-icon'],

	['type' => 'btn'],
];

// buttons
$buttons = [
	// ['type' => 'edit'],
	['type' => 'delete'],
];

// regenerate images
if (isset($_POST['generate'])) {
	$id = $_POST['ID'];

	if (!DBOld::find('custom FROM designs WHERE ID = ?', [$id])) {
		$res = DBOld::select('ID from items WHERE desID = ?', [$id]);

		while ($row = $res->fetch_assoc()) {
			$product = new Ecommerce\Product($row['ID'], 'type');
			$files = glob(PUBLIC_SERVER_ROOT . '/uploads/products/' . $product->type->slug . '/' . $product->id . '*');

			array_map('unlink', $files);
		}

		unlink(PUBLIC_SERVER_ROOT . '/uploads/designs/previews/' . $id . '.jpg');
		unlink(PUBLIC_SERVER_ROOT . '/uploads/designs/previews/' . $id . '_m.jpg');
	}

	redirect_old('#row' . $id);
}
