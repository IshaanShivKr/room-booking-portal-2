<?php

namespace App\Repositories;

use App\Models\Room;

class RoomRepository extends BaseRepository {
    public function findAll(): array {
        $sql = 'SELECT * FROM rooms';
        $stmt = $this->query($sql);
        $rows = $stmt->fetchAll();

        return array_map(fn($row) => new Room($row), $rows);
    }
}