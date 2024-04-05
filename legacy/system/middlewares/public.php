<?php

$public = DBOld::find('value FROM variables WHERE name = ?', ['public']);

if (!$public && !is_admin()) redirect_old('/offline');
