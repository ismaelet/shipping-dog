<?php

use Legacy\System\Classes\Query_Result;



function use_natural_keys(&$array) {
	$i = 0;
	$newArray = [];

	foreach ($array as $value) {
		$i++;
		$newArray[$i] = $value;
	}

	$array = $newArray;
}

function extract_subvalue(array $array, mixed $subKey) {
	foreach ($array as $key => $values) {
		$array[$key] = $values[$subKey];
	}

	return $array;
}

function array_to_sql($array) {
	$sqlArray = is_array($array) ? "'" . implode("','", $array) . "'" : '';
	return $sqlArray;
}
