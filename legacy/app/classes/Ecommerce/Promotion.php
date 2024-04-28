<?php

namespace Legacy\Classes\Ecommerce;

use \Legacy\System\Traits\DatabaseObject;

/**
 * Promotion class
 */
class Promotion {

	use \Legacy\System\Traits\DatabaseObject;

	public const TABLE = 'inventory';
	public const INDEX = 'id';
}
