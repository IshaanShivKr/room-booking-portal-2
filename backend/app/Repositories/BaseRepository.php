<?php

namespace App\Repositories;

use App\Exceptions\InternalServerException;
use PDO;

abstract class BaseRepository {
    protected PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    protected function query(string $sql, array $params = []): \PDOStatement {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (\PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            throw new InternalServerException("Database operation failed.");
        }
    }
}