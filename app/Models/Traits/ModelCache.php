<?php

namespace App\Models\Traits;

trait ModelCache {

	private $cache = [];

	private function getCache(string $key) {
		return $this->cache[$key] ?? null;
	}

	private function setCache(string $key, $value) {
		return $this->cache[$key] = $value;
	}
}
