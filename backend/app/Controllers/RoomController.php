<?php

namespace App\Controllers;

use App\Services\RoomService;
use App\Http\ApiResponse;

class RoomController {
    private RoomService $service;

    public function __construct(RoomService $service) {
        $this->service = $service;
    }

    public function index(): array
    {
        $rooms = $this->service->getAllRooms();
        return ApiResponse::success(
            array_map(fn($room) => $room->toArray(), $rooms)
        );
    }
}