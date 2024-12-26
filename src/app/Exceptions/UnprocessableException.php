<?php

namespace App\Exceptions;

use Exception;

class UnprocessableException extends Exception
{
    /**
     * @param array<string, string> $errors
     */
    public function __construct(
        private readonly array $errors,
    ) {
        parent::__construct('Unprocessable Entity');
    }

    /**
     * @return array<string, string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
