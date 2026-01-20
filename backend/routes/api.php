<?php

use App\Controllers\RoomController;
use App\Controllers\BookingController;

return [
    'GET' => [
        '/api/rooms' => [RoomController::class, 'index'],
    ],
    'POST' => [
        '/api/bookings' => [BookingController::class, 'store'],
    ],
];