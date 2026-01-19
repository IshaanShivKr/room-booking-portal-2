<?php

namespace App\Controllers;

use App\Services\RoomService;
use PDO;

class RoomController {
    private RoomService $service;

    public function __construct(PDO $pdo) {
        $this->service = new RoomService($pdo);
    }

    public function index(): array {
        return $this->service->getAllRooms();
    }
}