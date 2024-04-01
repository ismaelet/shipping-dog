<?php

trait ConstructFromDatabase {

	private $databaseEntry = [];

	function __construct(int $id = null) {
		$index = self::INDEX ?? 'id';
		$data = DB::row('* FROM ' . self::TABLE . ' WHERE ' . $index . ' = ?', [$id]);

		if ($data) {
			$this->databaseEntry = $data;
		} else {
			$columns = DB::select('COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?', [sys_config('database'), self::TABLE]);

			while ($column = $columns->fetch_assoc()) {
				$this->databaseEntry[$column['COLUMN_NAME']] = null;
			}
		}
	}
}
