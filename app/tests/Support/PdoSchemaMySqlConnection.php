<?php

namespace Tests\Support;

use Illuminate\Database\MySqlConnection;

class PdoSchemaMySqlConnection extends MySqlConnection
{
    public function getSchemaState($files = null, $processFactory = null)
    {
        return new PdoSchemaState($this, $files, $processFactory);
    }
}
