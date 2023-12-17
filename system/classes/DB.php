<?php

class DB {

	// generic query
	static function execute(string $query, $values = []) {
		global $con;

		$values = !empty($values) ? (array) $values : null;

		// prepare the query
		$stmt = $con->prepare($query);

		// execute the query
		if ($stmt) {
			$types = [];

			if (!empty($values)) {
				foreach ($values as &$value) {
					switch (true) {
						case $value && strval($value)[0] == 0:
							$types[] = 's';
							break;
						case is_int($value):
							$types[] = 'i';
							break;
						case is_numeric($value):
							$types[] = 'd';
							break;
						default:
							$types[] = 's';
					}
				}

				$stmt->bind_param(implode('', $types), ...$values);
			}

			$stmt->execute();
			$stmt->store_result();

			return new Query($stmt);
		} else {
			return false;
		}
	}

	// select several rows
	static function select(string $query, $values = []) {
		$query = 'SELECT ' . $query;
		$response = self::execute($query, $values);

		if ($response) {
			return $response->get_result();
		} else {
			return false;
		}
	}


	// select a single row
	static function row(string $query, $values = []) {
		$query = $query . ' LIMIT 1';
		$response = self::select($query, $values);

		return $response->fetch_assoc();
	}

	// select a specific value
	static function find(string $query, $values = []) {
		$field = substr($query, 0, strpos($query, ' FROM'));
		$response = self::row($query, $values);

		if ($response) {
			return $response[$field];
		} else {
			return false;
		}
	}

	// get a count of entries
	static function count(string $query, $values = []) {
		$query = 'COUNT(*) AS num FROM ' . $query;
		$response = self::row($query, $values);

		return $response['num'];
	}

	// get a sum of values
	static function sum(string $query, $values = []) {
		$field = substr($query, 0, strpos($query, ' FROM'));
		$table = substr($query, strpos($query, 'FROM ') + 5);
		$query = 'SUM(' . $field . ') AS tot FROM ' . $table;
		$response = self::row($query, $values);

		return $response['tot'];
	}

	// add an element
	static function add(string $query, $values = []) {
		global $con;

		$query = 'INSERT INTO ' . $query;

		if (!strpos($query, 'VALUES') && strpos($query, '(')) {
			$placeholders = [];

			if ($values) {
				for ($i = 1; $i <= count($values); $i++) {
					$placeholders[] = '?';
				}
			}

			$query .= ' VALUES (' . implode(',', $placeholders) . ')';
		}

		if (self::execute($query, $values)) {
			return $con->insert_id;
		} else {
			return false;
		}
	}

	// add an element or update it if it exists
	static function put(string $table, array $values, int $index = null) {
		$queryFields = [];
		$queryValues = [];
		$indexField = self::execute('SHOW columns FROM ' . $table . ' WHERE `Key` = "PRI"')->res[0]['Field'];

		foreach ($values as $field => $value) {
			$queryFields[] = $field;
			$queryValues[] = $value;
		}

		if ($index && self::exists($table . ' WHERE ' . $indexField . '=?', [$index])) {
			$queryFields = array_map(function ($value) {
				return $value . '=?';
			}, $queryFields);

			$queryFields = implode(', ', $queryFields);

			return self::update($table . ' SET ' . $queryFields . ' WHERE ' . $indexField . '=?', [...$queryValues, $index]);
		} else {
			$queryFields = implode(', ', [$indexField, ...$queryFields]);

			return self::add($table . ' (' . $queryFields . ')', [$index, ...$queryValues]);
		}
	}

	// update an element
	static function update(string $query, $values = []) {
		$query = 'UPDATE ' . $query;

		return self::execute($query, $values);
	}

	// delete an element
	static function delete(string $query, $values = []) {
		$query = 'DELETE FROM ' . $query;

		return self::execute($query, $values);
	}

	// check if an element exists 
	static function exists(string $query, $values = []) {
		$query = '1 FROM ' . $query . ' LIMIT 1';
		$response = self::select($query, $values);

		return $response->num_rows;
	}

	// check if a table exists
	static function table(string $table) {
		$query = "SHOW TABLES LIKE '" . $table . "'";
		$response = self::execute($query);

		return $response->num_rows;
	}
}
