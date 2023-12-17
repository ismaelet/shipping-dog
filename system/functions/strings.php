<?php

// format numbers
function format($n) {
	$n = (float) $n;
	return number_format($n, 2, '.', '');
}

function formatc($n) {
	$n = (float) $n;
	return number_format($n, 2, ',', '');
}

function format_bytes($bytes, $decimals = 0) {
	$symbols = array('B', 'KB', 'MB', 'GB', 'TB');

	$exp = floor(log($bytes) / log(1024));

	$formatedBytes = round($bytes / pow(1024, $exp), $decimals) . ' ' . $symbols[$exp];

	return $formatedBytes;
}

function condense_string(string $string) {
	return preg_replace('!\s+!', ' ', $string);
}

function strip_letters($string) {
	$number = preg_replace("/[^0-9]/", "", $string);
	$number = (float) $number;

	return $number;
}

function numberToWord($number, $lang = 'it') {
	$number = round($number);

	$word = '';

	// thousands
	if ($number >= 1000) {

		$thousands = floor($number / 1000);
		$number -= $thousands * 1000;

		$word .= $thousands == 1 ? 'mille' : digitToWord($thousands) . 'mila';
	}

	// hundreds
	if ($number >= 100) {

		$hundreds = floor($number / 100);
		$number -= $hundreds * 100;

		$word .= $hundreds == 1 ? 'cento' : digitToWord($hundreds) . 'cento';
	}

	// tens
	if ($number >= 20) {

		$tens = floor($number / 10);
		$number = $number - $tens * 10;

		switch ($tens) {
			case 2:
				$tensWord = 'venti';
				break;
			case 3:
				$tensWord = 'trenta';
				break;
			case 4:
				$tensWord = 'quaranta';
				break;
			case 5:
				$tensWord = 'cinquanta';
				break;
			case 6:
				$tensWord = 'sessanta';
				break;
			case 7:
				$tensWord = 'settanta';
				break;
			case 8:
				$tensWord = 'ottanta';
				break;
			case 9:
				$tensWord = 'novanta';
				break;
		}

		if (in_array($number, [1, 8])) $tensWord = substr($tensWord, 0, -1);

		$word .= $tensWord;
	}

	// numbers under 20
	if ($number) $word .= digitToWord($number);

	return $word;
}

// numbers till 19 to word conversion
function digitToWord($digit) {
	switch ($digit) {
		case 1:
			return 'uno';
		case 2:
			return 'due';
		case 3:
			return 'tre';
		case 4:
			return 'quattro';
		case 5:
			return 'cinque';
		case 6:
			return 'sei';
		case 7:
			return 'sette';
		case 8:
			return 'otto';
		case 9:
			return 'nove';
		case 10:
			return 'dieci';
		case 11:
			return 'undici';
		case 12:
			return 'dodici';
		case 13:
			return 'tredici';
		case 14:
			return 'quattordici';
		case 15:
			return 'quindici';
		case 16:
			return 'sedici';
		case 17:
			return 'diciassette';
		case 18:
			return 'diciotto';
		case 19:
			return 'diciannove';
		default:
			return null;
	}
}

function random_string($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyz';

	$charactersLength = strlen($characters);

	$randomString = '';

	for ($i = 0; $i < $length; $i++) $randomString .= $characters[rand(0, $charactersLength - 1)];

	return $randomString;
}

/*
* Dates
*/
function format_date(string $unformattedDate, string $format = 'd-m-Y', bool $replaceHypens = true) {
	$date = new DateTime($unformattedDate);
	$formattedDate = $date->format($format);

	if ($replaceHypens) {
		$formattedDate = str_replace('-', ' &middot; ', $formattedDate);
	}

	return $formattedDate;
}

function translate_date(string $date) {
	$months = [
		'January' => 'Gennaio',
		'February' => 'Febbraio',
		'March' => 'Marzo',
		'April' => 'Aprile',
		'May' => 'Maggio',
		'June' => 'Giugno',
		'July' => 'Luglio',
		'August' => 'Agosto',
		'September' => 'Settembre',
		'October' => 'Ottobre',
		'November' => 'Novembre',
		'December' => 'Dicembre',
	];

	foreach ($months as $originalName => $translatedName) {
		$date = str_replace($originalName, $translatedName, $date);
	}

	return $date;
}
