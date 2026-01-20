<?php

namespace App\Controllers;

use App\Services\BookingService;
use App\Http\ApiResponse;

class BookingController extends BaseController {
    private BookingService $service;

    public function __construct(BookingService $service) {
        $this->service = $service;
    }

    public function store(): array {
        $data = $this->getJsonInput();

        $this->validate($data, [
            'user_id', 'room_id', 'booking_date', 'start_time', 'end_time', 'purpose'
        ]);

        $booking_id = $this->service->makeBooking($data);

        return ApiResponse::success([
            'booking_id' => $booking_id,
            'message' => 'Booking created successfully',
        ], 201);
    }
}