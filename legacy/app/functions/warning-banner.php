<?php

function warning_banner() {

	$warning['text'] = DBOld::find('value FROM variables WHERE name = ?', ['warning']);
	$warning['color'] = DBOld::find('value FROM variables WHERE name = ?', ['warning_color']);
	$warning['start'] = DBOld::find('value FROM variables WHERE name = ?', ['warning_start']);
	$warning['end'] = DBOld::find('value FROM variables WHERE name = ?', ['warning_end']);

	if (empty($warning['text']) || strtotime($warning['start']) > strtotime('now') || strtotime($warning['end'] . '+1 day') < strtotime('now')) $warning = false;

	return $warning;
}
