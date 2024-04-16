<?php

namespace App\Http\Controllers\Admin\Resources;

use App\Http\Controllers\Admin\ResourceController;
use App\Models\Admin\AdminCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class AdminCategoryController extends ResourceController {

	/**
	 * Index action configuration
	 */
	protected array $orderBy = ['for_supervisors' => 'asc', 'order' => 'asc', 'name' => 'asc'];
	protected array $columns = [
		['label' => 'ID', 'name' => 'id'],
		['label' => 'Nome', 'name' => 'name'],
		['label' => 'Slug', 'name' => 'slug'],
		['label' => 'Ordine', 'name' => 'order', 'type' => 'int'],
		['label' => 'Per supervisori', 'name' => 'for_supervisors', 'type' => 'bool'],
	];

	/**
	 * Edit action configuration
	 */
	protected string $titleField = 'name';

	/**
	 * Index
	 */
	public function index(Request $request): View {
		// Prepare the query
		$this->query = AdminCategory::select('*');

		// Add search filter
		if ($request->search) {
			$this->query = $this->query->where('id', $request->search)
				->orWhere('name', 'like', '%' . $request->search . '%')
				->orWhere('slug', 'like', '%' . $request->search . '%');
		}

		// Add the buttons
		$this->buttons = [
			[
				'label' => 'Modifica',
				'icon' => 'edit',
				'color' => 'blue',
				'url' => '/' . Route::current()->uri() . '/{id}',
			],
			[
				'label' => 'Elimina',
				'icon' => 'delete',
				'name' => 'delete',
				'color' => 'red',
				'confirmation' => true,
			],
		];

		return $this->_index($request);
	}

	/**
	 * Edit action
	 */
	public function edit(int $id): View {
		$this->record = AdminCategory::find($id);

		return $this->_edit();
	}
}
