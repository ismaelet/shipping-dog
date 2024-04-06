<?php

// sections
$sections = DBOld::select('slug, category FROM admin_sections');

while ($section = $sections->fetch_assoc()) {
	RouteOld::add('/admin/' . $section['category'] . '/' . $section['slug'], function ($path) {
		global $id;

		$id = $_GET['id'] ?? null;

		// fetch section data
		$uriParts = explode('/', explode('admin/', $path)[1], 2);
		$section = DBOld::row('* FROM admin_sections WHERE slug=? AND category=?', [$uriParts[1], $uriParts[0]]);

		// setup view
		ViewOld::setPage('section-' . $section['category'] . '/' . $section['slug'])
			->setTitle($section['name'])
			->setTemplate(in_array($section['template'], ['admin', 'void']) ? $section['template'] : 'admin-' . $section['template'])
			->addMiddlewares(['admin'])
			->show();
	});
}
