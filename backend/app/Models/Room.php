<?php

namespace App\Models;

class Room {
    public int $id;
    public int $building_id;
    public string $room_number;
    public int $capacity;
    public string $type;
    public bool $is_active;

    public function __construct(array $data) {
        $this->id = (int)$data['id'];
        $this->building_id = (int)$data['building_id'];
        $this->room_number = $data['room_number'];
        $this->capacity = (int)$data['capacity'];
        $this->type = $data['type'];
        $this->is_active = (bool)$data['is_active'];
    } 
}