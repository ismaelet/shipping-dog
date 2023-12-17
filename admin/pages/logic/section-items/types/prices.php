<?php

//setup page
$tbl = 'prices_default';
$index = 'typeID';
$index_show = 'typeID';

//form
$inputs = [
	[
		['type' => 'lbl', 'text' => 'Prezzo'],
		['name' => 'price', 'text' => 'Prezzo'],
	],
	[
		['type' => 'lbl', 'text' => 'Guadagno da capi bianchi'],
		['name' => 'gain_w', 'text' => 'Guadagno da capi bianchi'],
	],
	[
		['type' => 'lbl', 'text' => 'Guadagni da capi colorati'],
		['name' => 'gain_c', 'text' => 'Guadagni da capi colorati'],
	],
];