<?php

/*
* Page configuration
*/
$pageConfig['add'] = false;

/*
* Query configuration
*/
$queryConfig['index'] = 'ID';
$queryConfig['table'] = 'designs';
$queryConfig['where-search'] = "name LIKE CONCAT('%',?,'%') OR ID = ? OR userID IN (SELECT userID FROM stores WHERE name LIKE CONCAT('%',?,'%'))";

// select fields
$fields = [
	['title' => 'ID', 'name' => 'ID'],
	['title' => 'Nome', 'name' => 'name', 'size' => 2],
	['title' => 'Artista', 'type' => 'find', 'name' => 'userID', 'find' => 'name FROM stores WHERE userID = ?', 'size' => 2],
	['title' => 'Vendite Totali', 'name' => 'sales'],
	['title' => 'Vendite Recenti', 'name' => 'rsales'],
	['title' => 'Attivo', 'name' => 'status', 'type' => 'boolean-icon', 'size' => 1],
	['title' => 'Data', 'name' => 'time'],

	['type' => 'btn'],
];

// buttons
$buttons = [
	['type' => 'edit'],
	['type' => 'delete'],
	['title' => 'Rigenera', 'name' => 'generate',  'color' => 'green'],
	['title' => 'Originale', 'url' => public_asset('/designs_originals/{{ID}}.png'), 'color' => 'yellow', 'download' => true],
	['title' => 'Originale su nero', 'url' => public_asset('/designs_originals/{{ID}}_dark.png'), 'color' => 'white', 'download' => true],
];

function _expand_buttons(&$button, $key, $row) {
	if ($key == 3) {

		if (file_exists(public_storage_path($url = '/designs_vector/' . $row['ID'] . '.ai'))) $button['url'] = public_asset($url);
		else if (file_exists(public_storage_path($url = '/designs_vector/' . $row['ID'] . '.pdf'))) $button['url'] = public_asset($url);
		else if (file_exists(public_storage_path($url = '/designs_originals/' . $row['ID'] . '.png'))) $button['url'] = public_asset($url);
	}

	if ($key == 4 && !file_exists(public_storage_path('/designs_originals/' . $row['ID'] . '_dark.png'))) $button = false;
}

// regenerate images
if (isset($_POST['generate'])) {
	$id = $_POST['ID'];

	if (!DBOld::find('custom FROM designs WHERE ID = ?', [$id])) {
		$res = DBOld::select('ID from items WHERE desID = ?', [$id]);

		while ($row = $res->fetch_assoc()) {
			$product = new Ecommerce\Product($row['ID'], 'type');
			$files = glob(public_storage_path('/products/' . $product->type->slug . '/' . $product->id . '*'));

			array_map('unlink', $files);
		}

		unlink(public_storage_path('/designs/previews/' . $id . '.jpg'));
		unlink(public_storage_path('/designs/previews/' . $id . '_m.jpg'));
	}

	redirect_old('#row' . $id);
}
