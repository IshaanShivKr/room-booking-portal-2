<?php

namespace App\Repositories;

use App\Models\Booking;

class BookingRepository extends BaseRepository {
    public function create(Booking $booking): int {
        $sql = 'INSERT INTO bookings (user_id, room_id, booking_date, start_time, end_time, purpose)
                VALUES (:user_id, :room_id, :booking_date, :start_time, :end_time, :purpose)';
        $this->query($sql, [
            'user_id' => $booking->user_id,
            'room_id' => $booking->room_id,
            'booking_date' => $booking->booking_date,
            'start_time' => $booking->start_time,
            'end_time' => $booking->end_time,
            'purpose' => $booking->purpose
        ]);
        $booking_id = $this->pdo->lastInsertId();
        return $booking_id;
    }

    /**
     * TODO: Logic to check if a room is already occupied
     */
    public function hasOverlap(int $roomId, string $date, string $start, string $end): bool {
        // This will eventually contain a SELECT COUNT(*) query with complex time logic
        return false; 
    }
}