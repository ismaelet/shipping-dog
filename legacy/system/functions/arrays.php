<?php

use Legacy\System\Classes\Query_Result;

function explode_last_legacy($separator, $string) {
	$array = explode($separator, $string);
	return end($array);
}

function use_natural_keys(&$array) {
	$i = 0;
	$newArray = [];

	foreach ($array as $value) {
		$i++;
		$newArray[$i] = $value;
	}

	$array = $newArray;
}

function sort_subvalue(&$array, $key) {
	usort($array, function ($a, $b) use ($key) {
		return $a[$key] <=> $b[$key];
	});
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
