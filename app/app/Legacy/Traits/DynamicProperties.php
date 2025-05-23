<?php

namespace App\Legacy\Traits;

trait DynamicProperties {

	private $data = [];

	function __get($property) {
		$method = 'get' . ucfirst($property);

		switch (true) {
			case array_key_exists($property, $this->data):
				// return a property already stored in $data
				return $this->data[$property];

			case isset($this->databaseEntry) && array_key_exists($property, $this->databaseEntry):
				// pass a value stored in $databaseEntry to $data and call the additional get method, if it exists
				$this->data[$property] = $this->databaseEntry[$property];

				if (method_exists($this, $method)) {
					$this->data[$property] = $this->$method();
				}

				return $this->data[$property];

			case method_exists($this, $method):
				// call the get method
				return $this->data[$property] = $this->$method();

			default:
				$this->data[$property] = null;

				$error = 'Undefined property ' . self::class . '::' . $property;

				if (env('DEBUG')) {
					trigger_error($error);
					debug_print_backtrace();
				}

				error_log($error);
		}
	}

	public function resetDynamicProperties() {
		$this->data = [];
	}

	public function reset() {
		$this->data = [];
		$this->__construct($this->id);
	}
}
