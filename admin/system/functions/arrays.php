<?php

function sort_subvalue(&$array, $key) {
	usort($array, function ($a, $b) use ($key) {
		return $a[$key] <=> $b[$key];
	});
}

function bump(&$array) {
	array_unshift($array, '');
	unset($array[0]);
}

function debug($array) {
	exit('<pre>' . print_r($array, true) . '</pre>');
}

function soft_debug($array) {
	print_r('<pre>' . print_r($array, true) . '</pre>');
}

function explode_last($separator, $string) {
	$array = explode($separator, $string);
	return end($array);
}

function array_to_sql($array) {
	$sqlArray = is_array($array) ? "'" . implode("','", $array) . "'" : '';
	return $sqlArray;
}
