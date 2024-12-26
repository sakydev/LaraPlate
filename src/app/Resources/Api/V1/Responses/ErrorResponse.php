<?php

namespace App\Resources\Api\V1\Responses;

use Illuminate\Http\JsonResponse;

class ErrorResponse extends JsonResponse
{
    /**
     * @param array<int|string, mixed>|string $error
     * @param array<string, string> $headers
     * */
    public function __construct(array|string $error, int $status, array $headers = [], int $options = 0)
    {
        parent::__construct(
            [
                'status' => 'error',
                'errors' => is_array($error) ? $error : [phrase($error)],
            ],
            $status,
            $headers,
            $options,
        );
    }
}
