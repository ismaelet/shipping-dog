<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ArrayHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class AdminFormController extends Controller {

	protected ?object $record = null;
	protected ?string $title = null;

	/**
	 * Display the form
	 */
	protected function view(): View {
		// Set title, if not set already
		if (!$this->title) {
			$this->title = AdminController::getSection()->name;
		}

		// Get the view name
		$viewName = ArrayHelper::explodeLast('.', Route::currentRouteName())[0] . '.manage';

		// Return the view
		return view($viewName)
			->with([
				'title' => $this->title,
				'record' => $this->record,
				'viewName' => $viewName,
				'type' => $this->record ? 'edit' : 'add',
			]);
	}
}
