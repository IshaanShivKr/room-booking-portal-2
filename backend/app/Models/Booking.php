<?php

namespace App\Models;

class Booking extends BaseModel {
    public ?int $id = null;
    public int $user_id;
    public int $room_id;
    public string $booking_date;
    public string $start_time;
    public string $end_time;
    public string $status;
    public string $purpose;
    public string $created_at;
}
