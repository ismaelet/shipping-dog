<?php

/*
* Page configuration
*/
$pageConfig['return-page'] = 'addresses';

/*
* Query configuarion
*/
$queryConfig['table'] = 'variables';
$queryConfig['index-field'] = 'name';
$queryConfig['title-field'] = 'name';
$queryConfig['entry'] = DB::row('id, name FROM ' . $queryConfig['table'] . ' WHERE name="log_date"');

/*
* Fields
*/
$inputs = [
	[
		['type' => 'lbl', 'text' => 'Cancella fino al (escluso)',],
		[
			'type' => 'date',
			'name' => 'value',
			'value' => date('Y-m-d'),
		]
	],
];
