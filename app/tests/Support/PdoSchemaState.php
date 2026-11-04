<?php

namespace Tests\Support;

use Illuminate\Database\Schema\MySqlSchemaState;

/**
 * Loads schema dumps through PDO so the mysql client binary is not required.
 */
class PdoSchemaState extends MySqlSchemaState
{
    public function load($path)
    {
        $this->connection->getPdo()->exec(file_get_contents($path));
    }
}
