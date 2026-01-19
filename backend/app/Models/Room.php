<?php

namespace App\Models;

Use App\Contracts\Arrayable;

class Room implements Arrayable {
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

    public function toArray(): array {
        return [
            'id' => $this->id,
            'building_id' => $this->building_id,
            'room_number' => $this->room_number,
            'capacity' => $this->capacity,
            'type' => $this->type,
            'is_active' => $this->is_active,
        ];
    }
}