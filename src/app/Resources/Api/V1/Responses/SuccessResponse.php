<?php

namespace App\Resources\Api\V1\Responses;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SuccessResponse extends JsonResponse
{
    /**
     * @param array<string, mixed> $content
     * @param array<string, string> $headers
     * */
    public function __construct(
        string $message,
        array $content = [],
        int $status = Response::HTTP_OK,
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
