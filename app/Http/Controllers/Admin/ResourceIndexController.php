<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

trait ResourceIndexController {

	/**
	 * Index configuration
	 */
	protected Builder $query;
	protected array $orderBy = ['id' => 'desc'];
	protected string $orderDirection = 'desc';
	protected array $columns = [];
	protected array $buttons = [];
	protected int $pageSize = 50;
	protected bool $searchBar = true;

	/**
	 * Index
	 *
	 * Display the index view with the configured settings.
	 *
	 * @param Request $request The HTTP request object.
	 * @return View The index view.
	 */
	protected function _index(Request $request): View {
		// Display the view
		return view('components.dashboard.table.index')->with([
			'title' => $this->sectionTitle(),
			'columns' => $this->columns(),
			'records' => $this->records($request),
			'recordCount' => $this->recordCount(),
			'mainOrderBy' => $this->mainOrderBy(),
			'orderDirection' => $this->orderDirection($request),
			'buttons' => $this->buttons,
			'searchBar' => $this->searchBar,
		]);
	}

	/**
	 * Get the record count.
	 *
	 * @return int The number of records.
	 */
	private function recordCount(): int {
		return $this->query->count();
	}

	/**
	 * Get the records.
	 *
	 * @param Request $request The request object.
	 * @return Collection The collection of records.
	 */
	private function records(Request $request): Collection {
		$records = clone $this->query;
		$currentPage = $request->page ?: 1;

		foreach ($this->orderBy($request) as $field => $direction) {
			$records->orderBy($field, $direction);
		}

		return $records
			->skip(($currentPage - 1) * $this->pageSize)
			->take($this->pageSize)
			->get();
	}

	/**
	 * Get the order field.
	 *
	 * @param Request $request The request object.
	 * @return array The order field.
	 */
	private function orderBy(Request $request): array {
		if ($request->order_by) {
			return $this->orderBy = [$request->order_by => $this->orderDirection($request)];
		}

		return $this->orderBy;
	}

	/**
	 * Get the order direction.
	 *
	 * @param Request $request The request object.
	 * @return string The order direction.
	 */
	private function orderDirection(Request $request): string {
		if ($request->order_direction) {
			return $request->order_direction;
		}

		return reset($this->orderBy);
	}

	/**
	 * Returns the first key of the $orderBy array.
	 *
	 * @return string The first key of the $orderBy array.
	 */
	private function mainOrderBy(): string {
		return array_key_first($this->orderBy);
	}

	/**
	 * Get the columns.
	 *
	 * @return array The columns.
	 */
	private function columns(): array {
		if (!empty($this->buttons)) {
			$this->columns[] = [];
		}

		return $this->columns;
	}
}
