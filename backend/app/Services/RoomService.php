<?php

namespace App\Services;

use App\Repositories\RoomRepository;
use PDO;

class RoomService {
    private RoomRepository $repo;

    public function __construct(PDO $pdo) {
        $this->repo = new RoomRepository($pdo);
    }

    public function getAllRooms(): array {
        return $this->repo->findAll();
    }
}