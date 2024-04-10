<?php

// fetch current section

use App\Models\Admin\AdminCategory;
use App\Models\Admin\AdminSection;

$currentSection = false;

if (strpos(ViewOld::$name, '-')) {
	$currentPath = explode('/', explode('section-', ViewOld::$name)[1], 2);
	$currentCategory = AdminCategory::where('slug', $currentPath[0])->first();
	$currentSection = $currentCategory ? AdminSection::where('slug', $currentPath[1])->where('category_id', $currentCategory->id)->first() : null;
}

// fetch categories
$menu['categories'] = [];
$queryManager = is_supervisor() ? '' : 'for_supervisors=0';

$categories = $queryManager ?
	AdminCategory::where($queryManager)->orderBy('for_supervisors')->orderBy('order')->orderBy('name')->get() :
	AdminCategory::orderBy('for_supervisors')->orderBy('order')->orderBy('name')->get();

foreach ($categories as $categoryData) {
	$category = [];
	$category['name'] = $categoryData->name;
	$category['slug'] = $categoryData->slug;
	$category['checked'] = $currentSection && $category['slug'] == $currentSection['category'];

	$sections = $queryManager ?
		AdminSection::where('category_id', $categoryData->id)->where($queryManager)->orderBy('order')->orderBy('name')->get() :
		AdminSection::where('category_id', $categoryData->id)->orderBy('order')->orderBy('name')->get();

	foreach ($sections as $sectionData) {
		if (!$sectionData->hidden && $sectionData->category) {
			$section = [];
			$section['name'] = $sectionData->name;
			$section['checked'] = $currentSection && $category['checked'] && ($sectionData->slug == $currentSection['slug'] || ($sectionData->hidden && str_replace('add', 'list',  $currentSection['slug'])));
			$section['url'] = '/admin/' . $sectionData->category->slug . '/' . $sectionData->slug;

			$category['sections'][] = $section;
		}
	}

	$menu['categories'][] = $category;
}

$navigationLogo = embed_icon(LOGO_DIR . 'logo', ['menu__logo']);
