<?php

namespace App\Resources\Api\V1\Responses;

use Illuminate\Http\JsonResponse;

class SuccessResponse extends JsonResponse
{
    /**
     * @param array<string, mixed> $content
     * @param array<string, string> $headers
     * */
    public function __construct(
        string $message,
        array $content = [],
        int $status = 200,
        array $headers = [],
        int $options = 0,
    ) {
        parent::__construct(
            [
                'success' => true,
                'errors' => null,
                'message' => phrase($message),
                'content' => $content,
            ],
            $status,
            $headers,
            $options,
        );
    }
}
