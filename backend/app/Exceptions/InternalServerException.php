<?php
namespace App\Exceptions;

use Exception;

class InternalServerException extends Exception {
    public function __construct(string $message = "Internal server error", int $code = 500) {
        parent::__construct($message, $code);
    }
}
