<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AdminSection;
use Illuminate\Support\Facades\Request;

class AdminController extends Controller {

	/**
	 * Get the current section record
	 */
	public static function getSection(): AdminSection {
		// Get the current section
		$sectionPath = explode('/', Request::path(), 3);
		$groupSlug = $sectionPath[1];
		$sectionSlug = $sectionPath[2];

		if (strpos($sectionSlug, "edit") !== false) {
			$sectionSlug = strstr($sectionSlug, "edit", true) . "edit";
			$sectionSlug = str_replace("edit", "add", $sectionSlug);
		}

		$section = AdminSection::join('admin_categories', 'admin_categories.id', '=', 'admin_sections.category_id')
			->where('admin_categories.slug', $groupSlug)
			->where('admin_sections.slug', $sectionSlug)
			->select('admin_sections.*')
			->first();

		return $section;
	}
}
