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
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                if (is_numeric($value) && strpos($key, 'id') !== false) {
                    $this->{$key} = (int)$value;
                } elseif ($key === 'capacity') {
                    $this->{$key} = (int)$value;
                } elseif ($key === 'is_active') {
                    $this->{$key} = (bool)$value;
                } else {
                    $this->{$key} = $value;
                }
            }
        }
    }

    public function toArray(): array {
        return get_object_vars($this);
    }
}