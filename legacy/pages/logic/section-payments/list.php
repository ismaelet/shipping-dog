<?php

/*
* Page configuration
*/
// $pageConfig['add'] = false;
$pageConfig['row-action'] = 'add?id={{id}}';

/*
* Query configuration
*/
$queryConfig['table'] = 'stores_payments';
$queryConfig['index'] = 'ID';
$queryConfig['select'] = ['stores_payments.ID', 'stores_payments.value', 'stores_payments.other', 'stores_payments.time', 'stores.name as sellerName'];
$queryConfig['from'] = 'stores_payments INNER JOIN stores ON stores_payments.storeID = stores.userID';
$queryConfig['where-search'] = "stores.name LIKE CONCAT('%',?,'%')";

/*
* List fields
*/
$fields = [
	['title' => 'ID', 'name' => 'ID'],
	['title' => 'Negozio', 'name' => 'sellerName', 'size' => 3],
	['title' => 'Valore', 'name' => 'value', 'parse' => 'price', 'size' => 1],
	['title' => 'Pagamento ordinario', 'name' => 'other', 'type' => 'boolean-icon', 'parse' => 'flip', 'size' => 1],
	['title' => 'Data', 'name' => 'time', 'size' => 1],

	['type' => 'btn'],
];

$buttons = [
	//['type' => 'edit'],
	['title' => 'Elimina', 'color' => 'red', 'name' => 'delete-expand', 'warning' => 'Sei sicuro di voler eliminare il pagamento?'],
];

/*
* Table interactions
*/
if (isset($_POST['delete-expand'])) {
	$id = $_POST['ID'];
	$payment = DBOld::row('* FROM stores_payments WHERE id = ?', $id);

	if ($payment['other']) DBOld::update('stores SET flat=flat-? WHERE userID = ?', [$payment['value'], $payment['storeID']]);
	else DBOld::update('stores SET payed=payed-? WHERE userID = ?', [$payment['value'], $payment['storeID']]);

	DBOld::delete('stores_payments WHERE ID = ?', [$id]);

	redirect_old();
}
