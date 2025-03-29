<?php

namespace Legacy\System\Classes;

trait ObjectInteractions {

	private $data = [];

	function __get($name) {
		if (array_key_exists($name, $this->data)) {
			return $this->data[$name];
		} else {
			$getMethod = 'get' . ucfirst($name);

			return $this->data[$name] = $this->$getMethod();
		}
	}
}
