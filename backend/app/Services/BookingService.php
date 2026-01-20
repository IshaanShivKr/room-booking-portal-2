<?php

namespace App\Services;

use App\Models\Booking;
use App\Repositories\BookingRepository;
use App\Exceptions\ValidationException;

class BookingService {
    private BookingRepository $repo;

    public function __construct(BookingRepository $repo) {
        $this->repo = $repo;
    }

    public function makeBooking(array $data): int {
        $booking = new Booking($data);
        $this->validateBookingRules($booking);
        return $this->repo->create($booking);
    }

    private function validateBookingRules(Booking $booking): void {
        // 1. TODO: Check if room is active/available in RoomRepository
        // 2. TODO: Check for time overlaps (Double-booking) in BookingRepository
        // 3. TODO: Validate user permissions or limits (e.g., max 3 bookings per day)


        if (strtotime($booking->end_time) <= strtotime($booking->start_time)) {
            throw new ValidationException("End time must be after start time.");
        }

        if (strtotime($booking->booking_date) < strtotime(date('Y-m-d'))) {
            throw new ValidationException("Cannot book for a past date.");
        }
    }
}