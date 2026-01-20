<?php

namespace App\Controllers;

use App\Exceptions\ValidationException;

abstract class BaseController {
    protected function getJsonInput(): array {
        $rawInput = file_get_contents('php://input');
        $data = json_decode($rawInput, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ValidationException("Invalid JSON format provided.");
        }

        return $data ?? [];
    }
}