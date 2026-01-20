<?php

namespace App\Models;

Use App\Models\BaseModel;

class Room extends BaseModel {
    public int $id;
    public int $building_id;
    public string $room_number;
    public int $capacity;
    public string $type;
    public bool $is_active;
}