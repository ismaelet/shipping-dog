<?php

namespace App\Legacy\Ecommerce\Design;

use Illuminate\Support\Facades\DB;
use LegacyDB;
use \Legacy\System\Traits\DatabaseObject;

class LogoGroup {

    use DatabaseObject;

    public const TABLE = 'extras_folder';
    public const INDEX = 'id';
    public const SELLER_INDEX = 'seller_id';

    /*
	* Get methods
	*/
    private function getId() {
        return $this->databaseEntry ? $this->databaseEntry[self::INDEX] : null;
    }

    public function getName() {
        return $this->name;
    }

    private function getLogos() {
        $rows = DB::select(
            Logo::INDEX . ' FROM ' . Logo::TABLE .
                ' WHERE ' . Logo::LOGO_GROUP_INDEX . ' = ?',
            [$this->id]
        );

        $logos = [];

        while ($row = $rows->fetch_assoc()) {
            $logos[] = new Logo($row[Logo::INDEX]);
        }

        return $logos;
    }
}
