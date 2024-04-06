<?php

$dett_str = $apply = '';

$desID = $row['desID'];
$storeID = $row['storID'];

if ($desID) $apply = DBOld::find('name FROM designs WHERE ID = ?', [$desID]);
else if ($storeID) $apply = DBOld::find('name FROM stores WHERE userID = ?', [$storeID]);

if ($row['typeID']) $apply .= 'Tipo: ' . DBOld::find('name_it FROM items_types WHERE ID = ?', [$row['typeID']]);
if ($row['colorID']) $apply .= 'Colore: ' . DBOld::find('name_it FROM items_colors WHERE ID = ?', [$row['colorID']]);

if (empty($apply)) $apply = 'Tutti';

$inputs[2]['value'] = $apply;

//code data
$codeMod = $row['mod_us'] + $row['mod_them'];
$codeFlat = $row['flat_us'] + $row['flat_them'];

if ($codeMod) $mod_str = '-' . ($codeMod) . '% (' . $row['mod_us'] . '+' . $row['mod_them'] . ')';
else $mod_str = '0 %';

$inputs[3]['value'] = $mod_str;
$inputs[3]['value'] = 'aaaa';

if ($row['zero'] == 1) $flat_str = '- Guadagno';
else if ($codeFlat) $flat_str = '-' . ($codeFlat) . '€ (' . $row['flat_us'] . '+' . $row['flat_them'] . ')';
else $flat_str = '0 €';

$inputs[4]['value'] = $flat_str;
$inputs[4]['value'] = 'aaaa';

//type
$type = DBOld::find('name_it FROM items_types WHERE ID = ?', [$row['typeID']]) ?: 'Tutti';

//color
$color = DBOld::find('name_it FROM items_colors WHERE ID = ?', [$row['colorID']]) ?: 'Tutti';

//time
$time_start = date('Y-m-d', strtotime($row['time_start']));
$time_end = date('Y-m-d', strtotime($row['time_end']));

$exp = (strtotime($row['time_end']) < strtotime('now')) ? true : false;

$inputs[5]['value'] = $time_start;
$inputs[6]['value'] = $time_end;

//details
if ($exp) $dett_str .= '<span style="color: #FF0000; font-weight: bold;">SCADUTO</span>, ';
if ($row['permanent']) $dett_str .= 'riusabile, ';
if (!$row['multiple']) $dett_str .= 'solo per il primo articolo, ';
if ($row['shipping']) $dett_str .= 'azzera le spedizioni, ';
if ($row['zero']) $dett_str .= "nessun guadagno per l'artista, ";
if ($row['nogain']) $dett_str .= 'sconto uguale al guadagno, ';
$dett_str = rtrim($dett_str, ', ');

$inputs[7]['value'] = $dett_str;
