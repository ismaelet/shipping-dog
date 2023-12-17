<?php

function warning_banner() {

	$warning['text'] = DB::find('value FROM variables WHERE name=?', ['warning']);
	$warning['color'] = DB::find('value FROM variables WHERE name=?', ['warning_color']);
	$warning['start'] = DB::find('value FROM variables WHERE name=?', ['warning_start']);
	$warning['end'] = DB::find('value FROM variables WHERE name=?', ['warning_end']);

	if (empty($warning['text']) || strtotime($warning['start']) > strtotime('now') || strtotime($warning['end'] . '+1 day') < strtotime('now')) $warning = false;

	return $warning;
}
