<?php

/*
* Page configuration
*/
$pageConfig['add'] = false;

/*
* Query configuration
*/
$queryConfig['index'] = 'id';
$queryConfig['table'] = 'designs';
$queryConfig['where-search'] = "name LIKE CONCAT('%',?,'%') OR ID = ? OR user_id IN (SELECT user_id FROM stores WHERE name LIKE CONCAT('%',?,'%'))";

// select fields
$fields = [
	['title' => 'id', 'name' => 'id'],
	['title' => 'Nome', 'name' => 'name', 'size' => 2],
	['title' => 'Artista', 'type' => 'find', 'name' => 'user_id', 'find' => 'name FROM stores WHERE user_id = ?', 'size' => 2],
	['title' => 'Vendite Totali', 'name' => 'sales'],
	['title' => 'Vendite Recenti', 'name' => 'recent_sales'],
	['title' => 'Attivo', 'name' => 'status', 'type' => 'boolean-icon', 'size' => 1],
	['title' => 'Data', 'name' => 'time'],

	['type' => 'btn'],
];

// buttons
$buttons = [
	['type' => 'edit'],
	['type' => 'delete'],
	['title' => 'Rigenera', 'name' => 'generate',  'color' => 'green'],
	['title' => 'Originale', 'url' => public_asset('designs_originals/{{id}}.png'), 'color' => 'yellow', 'download' => true],
	['title' => 'Originale su nero', 'url' => public_asset('designs_originals/{{id}}_dark.png'), 'color' => 'white', 'download' => true],
];

function _expand_buttons(&$button, $key, $row) {
	if ($key == 3) {

		if (file_exists(public_storage($url = '/designs_vector/' . $row['id'] . '.ai'))) $button['url'] = public_asset($url);
		else if (file_exists(public_storage($url = '/designs_vector/' . $row['id'] . '.pdf'))) $button['url'] = public_asset($url);
		else if (file_exists(public_storage($url = '/designs_originals/' . $row['id'] . '.png'))) $button['url'] = public_asset($url);
	}

	if ($key == 4 && !file_exists(public_storage('/designs_originals/' . $row['id'] . '_dark.png'))) $button = false;
}

// regenerate images
if (isset($_POST['generate'])) {
	$id = $_POST['id'];

	if (!\Legacy\System\Classes\LegacyDB::find('custom FROM designs WHERE id = ?', [$id])) {
		$res = \Legacy\System\Classes\LegacyDB::select('id from items WHERE design_id = ?', [$id]);

		while ($row = $res->fetch_assoc()) {
			$product = new \Legacy\Classes\Ecommerce\Product($row['id'], 'type');
			$files = glob(public_storage('/products/' . $product->type->slug . '/' . $product->id . '*'));

			array_map('unlink', $files);
		}

		unlink(public_storage('/designs/previews/' . $id . '.jpg'));
		unlink(public_storage('/designs/previews/' . $id . '_m.jpg'));
	}

	redirect_old('#row' . $id);
}
