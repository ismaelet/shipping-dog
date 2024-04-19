<?php

use App\Helpers\ArrayHelper;
use App\Models\Admin\AdminSection;

$sections = AdminSection::all();

foreach ($sections as $section) {
	RouteOld::add('/admin/' . $section->resource . '/' . $section->action, function ($path) {
		global $id;

		$id = $_GET['id'] ?? null;

		// fetch section data
		$uriParts = ArrayHelper::explodeLast('/', trim($path, '/admin'));
		$section = AdminSection::where('resource', $uriParts[0])
			->where('action', $uriParts[1])
			->first();

		$template = in_array($section->action, ['index']) ? 'admin-list' : 'admin-form';
		$template = in_array($section->id, [50]) ? 'admin' : $template;

		// setup view
		if ($section) {
			ViewOld::setPage('admin/' . $section->resource . '/' . $section->action)
				->setTitle($section->name)
				->setTemplate($template)
				->addMiddlewares(['admin'])
				->show();
		}
	});
}
