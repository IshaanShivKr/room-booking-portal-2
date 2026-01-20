<?php

namespace App\Repositories;

use App\Models\Room;

class RoomRepository extends BaseRepository {
    public function findAll(): array {
        $stmt = $this->query('SELECT * FROM rooms');
        $rows = $stmt->fetchAll();

        return array_map(fn($row) => new Room($row), $rows);
    }
}