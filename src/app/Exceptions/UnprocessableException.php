<?php

namespace App\Exceptions;

use Exception;

class UnprocessableException extends Exception
{
    public function __construct(
        private readonly array $errors,
    ) {
        parent::__construct('Unprocessable Entity');
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
