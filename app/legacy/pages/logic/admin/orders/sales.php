<?php

// select table
$tbl = 'sales';

// handle search
$query['filter'] = "design_id IN (SELECT id FROM designs WHERE name LIKE CONCAT('%',?,'%')) OR seller_id IN (SELECT user_id FROM stores WHERE name LIKE CONCAT('%',?,'%'))";

// select fields
$inputs = [
	['type' => 'hidden', 'field' => 'id'],

	['title' => 'id', 'field' => 'id', 'size' => 's'],
	['title' => 'Design', 'field' => 'design_id', 'find' => 'name FROM designs WHERE id = ?', 'size' => 'm'],
	['title' => 'Negozio', 'field' => 'seller_id', 'find' => 'name FROM stores WHERE user_id = ?', 'size' => 'm'],
	['title' => 'Tipo', 'field' => 'type_id', 'find' => 'name FROM product_types WHERE id = ?', 'size' => 'm'],
	['title' => 'Colore', 'field' => 'color_id', 'find' => 'name FROM product_colors WHERE id = ?', 'size' => 'm'],
	['title' => 'Codice', 'field' => 'code', 'size' => 'm'],
	['title' => 'Qt.', 'field' => 'amount', 'size' => 's'],
	['title' => 'Guadagno', 'field' => 'gain', 'show' => 'num2', 'size' => 's'],
	['title' => 'Data', 'field' => 'time', 'size' => 'm'],

	['type' => 'btn'],
];

$buttons = [
	['arch' => 'delete'],
];
