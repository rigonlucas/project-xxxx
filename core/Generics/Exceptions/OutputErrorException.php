<?php

namespace Core\Generics\Exceptions;

use Exception;

class OutputErrorException extends Exception
{
    public function __construct(
        string $message,
        int $code,
        Exception $previous = null,
        private ?array $errors = []
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getErrors(): ?array
    {
        return $this->errors;
    }
}
