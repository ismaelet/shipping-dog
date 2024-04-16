<?php

$dett_str = $apply = '';

$design_id = $row['design_id'];
$seller_id = $row['seller_id'];

if ($design_id) $apply = DBOld::find('name FROM designs WHERE id = ?', [$design_id]);
else if ($seller_id) $apply = DBOld::find('name FROM stores WHERE user_id = ?', [$seller_id]);

if ($row['type_id']) $apply .= 'Tipo: ' . DBOld::find('name FROM items_types WHERE id = ?', [$row['type_id']]);
if ($row['color_id']) $apply .= 'Colore: ' . DBOld::find('name FROM items_colors WHERE id = ?', [$row['color_id']]);

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
$type = DBOld::find('name FROM items_types WHERE id = ?', [$row['type_id']]) ?: 'Tutti';

//color
$color = DBOld::find('name FROM items_colors WHERE id = ?', [$row['color_id']]) ?: 'Tutti';

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
