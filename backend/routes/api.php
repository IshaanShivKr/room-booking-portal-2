<?php

use App\Controllers\RoomController;

return [
    'GET' => [
        '/api/rooms' => [RoomController::class, 'index'],
    ],
];