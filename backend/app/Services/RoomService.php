<?php

namespace App\Services;

use App\Repositories\RoomRepository;
use PDO;

class RoomService {
    private RoomRepository $repo;

    public function __construct(RoomRepository $repo) {
        $this->repo = $repo;
    }

    public function getAllRooms(): array {
        return $this->repo->findAll();
    }
}