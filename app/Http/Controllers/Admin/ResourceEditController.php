<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ArrayHelper;
use Illuminate\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View as FacadesView;

trait ResourceEditController {

	/**
	 * Store configuration
	 */
	protected ?object $record = null;
	protected string $titleField;

	/**
	 * Show
	 *
	 * Display the show view for a specific record.
	 *
	 * @param int $id The ID of the record to show.
	 * @return View The show view.
	 */
	protected function _edit(): View {
		return view($this->viewName())
			->with([
				'title' => $this->formTitle(),
				'record' => $this->record,
				'type' => $this->record ? 'edit' : 'add',
			]);
	}

	/**
	 * Get the form view name.
	 *
	 * @return string The form view name.
	 */
	private function viewName(): string {
		$viewName = Route::currentRouteName();

		return FacadesView::exists($viewName) ? $viewName : ArrayHelper::explodeLast('.', $viewName)[0] . '.show';
	}

	/**
	 * Get the form title.
	 *
	 * @return string The form title.
	 */
	private function formTitle(): string {
		return $this->record ? 'Modifica: ' . $this->record->{$this->titleField} : $this->section()->name;
	}
}
