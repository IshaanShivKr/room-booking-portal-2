<?php

namespace App\Repositories;

use App\Models\Room;
use PDO;

class RoomRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findAll(): array {
        try {
            $stmt = $this->pdo->query('SELECT * FROM rooms');
            $rows = $stmt->fetchAll();

            return array_map(fn($row) => new Room($row), $rows);

        } catch(\PDOException $e) {
            throw new \App\Exceptions\InternalServerException("Failed to fetch rooms");
        }
    }
}