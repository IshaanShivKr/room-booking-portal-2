<?php

declare(strict_types=1);

use App\Controllers\RoomController;
use App\Controllers\BookingController;
use App\Repositories\RoomRepository;
use App\Repositories\BookingRepository;
use App\Services\RoomService;
use App\Services\BookingService;

return [
    RoomController::class => function (PDO $pdo): RoomController {
        $roomRepository = new RoomRepository($pdo);
        $roomService    = new RoomService($roomRepository);

        return new RoomController($roomService);
    },

    BookingController::class => function (PDO $pdo): BookingController {
        $bookingRepository = new BookingRepository($pdo);
        $bookingService    = new BookingService($bookingRepository);

        return new BookingController($bookingService);
    },
];