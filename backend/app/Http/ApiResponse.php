<?php

namespace App\Http;

class ApiResponse {
    public static function success(mixed $data, int $code = 200): array {
        http_response_code($code);

        return [
            'status' => 'success',
            'data' => $data,
        ];
    }

    public static function error(string $message, int $code): array {
        http_response_code($code);

        return [
            'status' => 'error',
            'message' => $message,
        ];
    }
}
