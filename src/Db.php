<?php

namespace App;

use PDO;

class Db
{
    public static function get(): PDO
    {
        return new PDO('pgsql:host='.getenv('DB_HOST').';dbname='.getenv('DB_NAME'), getenv('DB_USER'), getenv('DB_PASS'));
    }
}