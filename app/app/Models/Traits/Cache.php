<?php

namespace App\Models\Traits;

trait Cache {

	private $cache = [];

	/**
	 * Set a value in the cache
	 */
	private function setCache(string $key, $value): self {
		$this->cache[$key] = $value;

		return $this;
	}

	/**
	 * Get a value from the cache
	 */
	private function getCache(string $key) {
		return $this->cache[$key] ?? null;
	}
}
