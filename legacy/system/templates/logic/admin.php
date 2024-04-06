<?php

// fetch current section
$currentSection = false;

if (strpos(ViewOld::$name, '-')) {
	$currentPath = explode('/', explode('section-', ViewOld::$name)[1], 2);
	$currentSection = DBOld::row('* FROM admin_sections WHERE category=? AND slug=?', [$currentPath[0], $currentPath[1]]);
}

// fetch categories
$menu['categories'] = [];
$queryManager = is_supervisor() ? '' : 'manager=0';

$categories = DBOld::select('* FROM admin_categories ' . ($queryManager ? 'WHERE ' . $queryManager : '') . ' ORDER BY manager ASC, sort ASC, name ASC');

while ($categoryData = $categories->fetch_assoc()) {
	$category = [];
	$category['name'] = $categoryData['name'];
	$category['slug'] = $categoryData['slug'];
	$category['checked'] = $currentSection && $category['slug'] == $currentSection['category'];

	$sections = DBOld::select('* FROM admin_sections WHERE category=? AND hidden=0 ' . ($queryManager ? 'AND ' . $queryManager : '') . ' ORDER BY sort ASC, name ASC', [$category['slug']]);

	while ($sectionData = $sections->fetch_assoc()) {
		$section = [];
		$section['name'] = $sectionData['name'];
		$section['checked'] = $currentSection && $category['checked'] && ($sectionData['slug'] == $currentSection['slug'] || ($sectionData['hidden'] && str_replace('add', 'list',  $currentSection['slug'])));
		$section['url'] = empty($sectionData['url']) ? '/admin/' . $sectionData['category'] . '/' . $sectionData['slug'] : $sectionData['url'];

		$category['sections'][] = $section;
	}

	$menu['categories'][] = $category;
}

$navigationLogo = embed_icon(LOGO_DIR . 'logo', ['menu__logo']);
