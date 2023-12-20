<?php

$public = DB::find('value FROM variables WHERE name = ?', ['public']);

if (!$public && !admin()) redirect('/offline');
