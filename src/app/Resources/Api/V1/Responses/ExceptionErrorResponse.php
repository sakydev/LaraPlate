<?php

namespace App\Resources\Api\V1\Responses;

use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Exceptions\UnprocessableException;
use App\Exceptions\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ExceptionErrorResponse extends ErrorResponse
{
    public function __construct(
        array|string $error,
        Throwable|null $exception = null,
        array $headers = [],
        int $options = 0
    ) {
        $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        $error = phrase($error);

        if ($exception) {
            $error = $exception->getMessage();

            if ($exception instanceof ValidationException || $exception instanceof UnprocessableException) {
                $status = Response::HTTP_UNPROCESSABLE_ENTITY;
            } elseif ($exception instanceof ForbiddenException) {
                $status = Response::HTTP_FORBIDDEN;
            } elseif ($exception instanceof NotFoundException) {
                $status = Response::HTTP_NOT_FOUND;
            }
        }

        parent::__construct(
            $error,
            $status,
            $headers,
            $options,
        );
    }
}
