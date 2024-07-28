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

    /**
     * Create a new product
     * @param array $data
     * @return int The ID of the newly created product
     */
    public function create(array $data): int {
        $sql = 'INSERT INTO product (name, description, size)
                VALUES (:name, :description, :size)';
    
        $pdo = $this->database->connect();
    
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindValue(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindValue(':size', $data['size'], PDO::PARAM_INT);
    
        $stmt->execute();
    
        return (int) $pdo->lastInsertId();
    }

    /**
     * Update an existing product
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool {
        $sql = 'UPDATE product
                SET name = :name,
                    description = :description,
                    size = :size
                WHERE id = :id';
    
        $pdo = $this->database->connect();
    
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindValue(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindValue(':size', $data['size'], PDO::PARAM_INT);
    
        return $stmt->execute();
    }
    

    /**
     * Delete a product by its id
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool {
        $sql = 'DELETE FROM product WHERE id = :id';

        $pdo = $this->database->connect();

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}