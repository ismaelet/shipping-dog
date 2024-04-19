<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use Illuminate\Support\Facades\Route;

trait ResourceEditController {

	/**
	 * Edit configuration
	 */
	protected ?object $record = null;
	protected string $titleField;

	/**
	 * View the edit form for a specific record.
	 *
	 * @param int $id The ID of the record to edit.
	 * @return View The edit view.
	 */
	protected function _edit(): View {
		return view(Route::currentRouteName())
			->with([
				'title' => $this->formTitle(),
				'record' => $this->record,
				'type' => $this->record ? 'edit' : 'add',
			]);
	}

	/**
	 * Get the form title.
	 *
	 * @return string The form title.
	 */
	private function formTitle(): string {
		return 'Modifica: ' . $this->record->{$this->titleField};
	}
}
