<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ArrayHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

trait ResourceUpdateController {

	/**
	 * Update action
	 *
	 * @param Request $request The ID of the record to show.
	 * @return RedirectResponse The redirect response.
	 */
	protected function _update(Request $request): RedirectResponse {
		$routeName = ArrayHelper::explodeLast('.', Route::currentRouteName())[0] . '.index';
		$routeParameters = json_decode($request->route()->index_parameters, true);

		return redirect()->route($routeName, $routeParameters);
	}
}
