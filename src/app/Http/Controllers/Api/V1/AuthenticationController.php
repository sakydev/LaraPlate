<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\UnprocessableException;
use App\Http\Controllers\Controller;
use App\Requests\Api\V1\Users\RegisterUserRequest;
use App\Resources\Api\V1\Responses\ErrorResponse;
use App\Resources\Api\V1\Responses\SuccessResponse;
use App\Resources\Api\V1\UserResource;
use App\Services\Users\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthenticationController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ) {
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        $input = $request->all();

        try {
            $createdUser = $this->userService->create($input);
            $userData = new UserResource($createdUser);

            return new SuccessResponse(
                'auth.success.registerOne',
                $userData->toArray(),
                Response::HTTP_CREATED,
            );
        } catch (UnprocessableException $exception) {
            return new ErrorResponse(
                $exception->getErrors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        } catch (Throwable $exception) {
            // Bugsnag::notifyException($exception);

            Log::error('Register: unexpected error', [
                'input' => $input,
                'error' => $exception->getMessage(),
            ]);

            return new ErrorResponse(
                [__('general.errors.unknown')],
                Response::HTTP_UNAUTHORIZED
            );
        }
    }

    public function login(Request $request): JsonResponse
    {
        $input = $request->only(['username', 'password']);

        try {
            $loggedIn = Auth::attempt($input);
            if (!$loggedIn) {
                return new ErrorResponse(
                    [__('auth.error.invalidCredentials')],
                    Response::HTTP_UNAUTHORIZED
                );
            }

            $userData = new UserResource(Auth::user(), true);

            return new SuccessResponse('auth.success.login', $userData->toArray());
        } catch (Throwable $exception) {
            // Bugsnag::notifyException($exception);

            Log::error('Login: unexpected error', [
                'input' => $input,
                'error' => $exception->getMessage(),
            ]);

            return new ErrorResponse(
                [__('general.errors.unknown')],
                Response::HTTP_UNAUTHORIZED
            );
        }
    }
}
