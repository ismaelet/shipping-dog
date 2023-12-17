<?php

/**
 * TEMP
 */

use Ecommerce\Coupon;

$tempCoupons = DB::select(Coupon::INDEX . ' FROM ' . Coupon::TABLE);

while ($tempCouponRow = $tempCoupons->fetch_assoc()) {
	$coupon = new Coupon($tempCouponRow[Coupon::INDEX]);

	if ($coupon->isExpired) {
		DB::delete(Coupon::TABLE . ' WHERE ' . Coupon::INDEX . ' = ?', [$coupon->id]);
	}
}

/*
* Query config
*/
$queryConfig['index'] = 'id';
$queryConfig['table'] = 'codes';
$queryConfig['where-search'] = "code LIKE CONCAT('%',?,'%') OR storID IN (SELECT userID FROM stores WHERE name LIKE CONCAT('%',?,'%'))";

/*
* Fields
*/
$fields = [
	['title' => 'ID', 'name' => 'id'],
	['title' => 'Codice', 'name' => 'code'],
	['title' => 'Attivo per', 'size' => 1],
	['title' => 'Sconto Percentuale'],
	['title' => 'Conto Fisso'],
	['title' => 'Attivo dal', 'name' => 'time_start', 'parse' => 'date-day'],
	['title' => 'Fino al', 'name' => 'time_end', 'parse' => 'date-day'],
	['title' => 'Dettagli', 'size' => 1],

	['type' => 'btn'],
];

$buttons = [
	['type' => 'edit'],
	['type' => 'delete'],
];

function _expand_results(&$entry, $field, $key, $row) {

	if ($key == 2) {
		if ($row['desID']) $entry['value'] = DB::find('name FROM designs WHERE id=?', [$row['desID']]);
		else if ($row['storID']) $entry['value'] = DB::find('name FROM stores WHERE userID=?', [$row['storID']]);
		else $entry['value'] = 'Tutti';
	} else if ($key == 3) {
		$entry['value'] = !$row['mod_us'] && !$row['mod_them'] ? 'nessuno' : '<span class="txt-bold">' . ($row['mod_us'] + $row['mod_them']) . '%</span>: ' . $row['mod_us'] . '% nostro, ' . $row['mod_them'] . '% del negozio';
	} else if ($key == 4) {
		$entry['value'] = !$row['flat_us'] && !$row['flat_them'] ? 'nessuno' : '<span class="txt-bold">' . ($row['flat_us'] + $row['flat_them']) . '€</span>: ' . $row['flat_us'] . '€ nostro, ' . $row['flat_them'] . '€ del negozio';
	} else if ($key == 7) {
		$info = [];

		if ($row['permanent']) $info[] = 'riusabile';
		if ($row['only_one']) $info[] = 'applicato solo al primo articolo';
		if ($row['zero']) $info[] = 'sconto uguale al guadagno';
		if ($row['nogain']) $info[] = 'azzera i guadagni';
		if ($row['one_per_acccount']) $info[] = 'può essere usato solo una volta per account';
		if ($row['shipping']) $info[] = 'toglie le spese di spedizione';

		$entry['value'] = empty($info) ? '-' : implode(', ', $info);
	}
}
