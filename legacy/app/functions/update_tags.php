<?php

function update_tags($slugs, $id, $slugs_old = false) {

	$slugs = strtolower(str_replace(['#'], '', $slugs));
	$slugs = explode(',', $slugs);
	$slugs = array_map('trim', $slugs);
	$slugs = array_filter($slugs);
	$slugs = array_unique($slugs);
	$slugs = array_slice($slugs, 0, 10);

	if ($slugs_old) foreach ($slugs_old as $slug) DBOld::update('sys_tags SET count=count-1 WHERE tag = ?', [$slug]);

	if (!empty($slugs)) foreach ($slugs as $slug) {

		if (DBOld::exists('sys_tags WHERE tag = ?', $slug)) DBOld::update('sys_tags SET count=count+1 WHERE tag = ?', [$slug]);
		else DBOld::add('sys_tags (tag, count) VALUES (?, 1)', [$slug]);
	}

	$slugs = implode(',', $slugs);

	if (DBOld::exists('designs_tags WHERE design_id = ?', [$id])) DBOld::update('designs_tags SET tags=? WHERE design_id = ?', [$slugs, $id]);
	else DBOld::add('designs_tags (design_id, tags) VALUES (?, ?)', [$id, $slugs]);
}
