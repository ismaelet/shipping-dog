<?php

/*
* Query configuration
*/
$queryConfig['table'] = 'variables';
$queryConfig['index-field'] = 'id';
$queryConfig['title-field'] = 'name';

/*
* Form fields
*/
$inputs = [
	['type' => 'text', 'name' => 'name', 'text' => 'Nome',],
	['type' => 'text', 'name' => 'value', 'text' => 'Valore',],
];
