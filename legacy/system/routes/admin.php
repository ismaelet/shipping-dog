<?php

use App\Models\Admin\AdminCategory;
use App\Models\Admin\AdminSection;

$sections = AdminSection::all();

foreach ($sections as $section) {
	if ($section->category) {
		RouteOld::add('/admin/' . $section->category->slug . '/' . $section->slug, function ($path) {
			global $id;

			$id = $_GET['id'] ?? null;

			// fetch section data
			$uriParts = explode('/', explode('admin/', $path)[1], 2);
			$category = AdminCategory::where('slug', $uriParts[0])->first();
			$section = AdminSection::where('slug', $uriParts[1])->where('category_id', $category->id)->first();

			// setup view
			ViewOld::setPage('section-' . $section->category->slug . '/' . $section->slug)
				->setTitle($section->name)
				->setTemplate(in_array($section->template, ['admin', 'void']) ? $section->template : 'admin-' . $section->template)
				->addMiddlewares(['admin'])
				->show();
		});
	}
}
