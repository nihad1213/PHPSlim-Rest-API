<?php
declare(strict_types=1);

namespace App;

use PDO;

class Database {

    /**
     * Connect Database
     * @return PDO
     */
    public function connect(): PDO {
        $dsn = "mysql:host=127.0.0.1;dbname=products_db;charset=utf8";

        $pdo = new PDO($dsn, 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        return $pdo;
    }
}