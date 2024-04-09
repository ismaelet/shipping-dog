<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AdminSection;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;

class AdminTableController extends Controller {

	/**
	 * Query configuration
	 */
	protected Builder $records;
	protected string $orderBy = 'id';
	protected string $orderDirection = 'desc';
	protected array $fields = [];
	protected array $buttons = [];
	protected int $pageSize = 50;

	/**
	 * Page configuration
	 */
	protected bool $interactiveRows = true;
	protected bool $addButton = false;
	protected bool $searchBar = true;

	/**
	 * Display the table
	 */
	public function view(Request $request): View {
		if ($request->has('order_by')) {
			$this->orderBy = $request->order_by;
		}

		if ($request->has('order_direction')) {
			$this->orderDirection = $request->order_direction;
		}

		if (!empty($this->buttons)) {
			$this->fields[]	= [];
		}

		// Display the view
		return view('components.templates.admin-table')
			->with([
				'section' => $this->getSection(),
				'fields' => $this->fields,
				'recordCount' => $this->records->count(),
				'records' => $this->fetchRecords($request),
				'buttons' => $this->buttons,
				'orderBy' => $this->orderBy,
				'orderDirection' => $this->orderDirection,
				'interactiveRows' => $this->interactiveRows,
				'addButton' => $this->addButton,
				'searchBar' => $this->searchBar,
				'pageSize' => $this->pageSize,
			]);
	}

	/**
	 * Get the current section record
	 */
	public function getSection(): AdminSection {
		// Get the current section
		$sectionPath = explode('/', FacadesRequest::path(), 3);
		$groupSlug = $sectionPath[1];
		$sectionSlug = $sectionPath[2];
		$section = AdminSection::where('category', $groupSlug)->where('slug', $sectionSlug)->first();

		return $section;
	}

	/**
	 * Get the current page
	 */
	public function currentPage(Request $request): int {
		return $request->page ?? 1;
	}

	/**
	 * Generate the data for the table
	 */
	private function fetchRecords(Request $request): Collection {
		return $this->records
			->orderBy($this->orderBy, $this->orderDirection)
			->skip(($this->currentPage($request) - 1) * $this->pageSize)
			->take($this->pageSize)
			->get();
	}
}
