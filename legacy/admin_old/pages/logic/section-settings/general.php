<?php

$public = DBOld::find('value FROM variables WHERE name = ?', ['public']);

if (isset($_POST['public'])) {

	foreach ($_POST['cbx_public'] as $public);

	DBOld::update('variables SET value=? WHERE name = ?', [$public, 'public']);
}
