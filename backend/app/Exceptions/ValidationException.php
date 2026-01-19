<?php
namespace App\Exceptions;

use Exception;

class ValidationException extends Exception {
    public function __construct(string $message = "Invalid input", int $code = 400) {
        parent::__construct($message, $code);
    }
}
