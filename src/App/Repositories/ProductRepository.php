<?php

declare(strict_types=1);

namespace App\Repostories;

use App\Database;
use PDO;


class ProductRepository {

    /**
     * Initialize class with a Database object
     * @param \App\Database $database
     */
    public function __construct(private Database $database) {
    }

    /**
     * Fetch All Data from Database
     * @return void
     */
    public function getAll(): array {
        $pdo = $this->database->connect();

        $stmt = $pdo->query("SELECT * FROM product");
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}