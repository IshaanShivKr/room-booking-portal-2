<?php

namespace App\Models;

use App\Contracts\Arrayable;

abstract class BaseModel implements Arrayable {
    public function __construct(array $data) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                if (is_numeric($value) && (strpos($key, 'id') !== false || $key === 'capacity')) {
                    $this->{$key} = (int)$value;
                } elseif ($key === 'is_active' || $key === 'status_flag') {
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