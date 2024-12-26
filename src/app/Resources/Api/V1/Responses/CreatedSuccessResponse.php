<?php

namespace App\Resources\Api\V1\Responses;

use Symfony\Component\HttpFoundation\Response;

class CreatedSuccessResponse extends SuccessResponse
{
    /**
     * @param array<string, mixed> $content
     * @param array<string, string> $headers
     * */
    public function __construct(
        string $message,
        array $content = [],
        array $headers = [],
        int $options = 0,
    ) {

        parent::__construct($message, $content, Response::HTTP_CREATED, $headers, $options);
    }
}
