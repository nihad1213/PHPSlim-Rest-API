<?php

declare(strict_types=1);

namespace App\Repositories;

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

    /**
     * Get product for it's id
     * @param int $id
     * @return array|bool
     */
    public function getByID(int $id): array|bool {
        $sql = 'SELECT *
                FROM product
                WHERE id = :id';

        $pdo = $this->database->connect();

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}