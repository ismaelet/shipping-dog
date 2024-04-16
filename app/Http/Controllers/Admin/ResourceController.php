<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AdminSection;
use Illuminate\Support\Facades\Route;

class ResourceController extends Controller {

	use ResourceIndexController, ResourceEditController;

	/**
	 * Get the section.
	 *
	 * @return AdminSection The admin section.
	 */
	private function section(): AdminSection {
		return AdminSection::findBySlug(Route::currentRouteName());
	}

	/**
	 * Get the title of the section.
	 *
	 * @return string The title of the section.
	 */
	private function sectionTitle(): string {
		return $this->section()->name;
	}
}
