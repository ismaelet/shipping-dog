<?php

function update_tags($slugs, $id, $slugs_old = false) {

	$slugs = strtolower(str_replace(['#'], '', $slugs));
	$slugs = explode(',', $slugs);
	$slugs = array_map('trim', $slugs);
	$slugs = array_filter($slugs);
	$slugs = array_unique($slugs);
	$slugs = array_slice($slugs, 0, 10);

	if ($slugs_old) foreach ($slugs_old as $slug) DB::update('sys_tags SET count=count-1 WHERE tag = ?', [$slug]);

	if (!empty($slugs)) foreach ($slugs as $slug) {

		if (DB::exists('sys_tags WHERE tag = ?', $slug)) DB::update('sys_tags SET count=count+1 WHERE tag = ?', [$slug]);
		else DB::add('sys_tags (tag, count) VALUES (?, 1)', [$slug]);
	}

	$slugs = implode(',', $slugs);

	if (DB::exists('designs_tags WHERE desID = ?', [$id])) DB::update('designs_tags SET tags=? WHERE desID = ?', [$slugs, $id]);
	else DB::add('designs_tags (desID, tags) VALUES (?, ?)', [$id, $slugs]);
}
