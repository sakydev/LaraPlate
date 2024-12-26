<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Requests\Api\V1\Users\UpdateUserRequest;
use App\Resources\Api\V1\Responses\ExceptionErrorResponse;
use App\Resources\Api\V1\Responses\SuccessResponse;
use App\Resources\Api\V1\UserResource;
use App\Services\Users\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        /**
         * @var User $user;
         */
        $user = Auth::user();
        $parameters = $request->only(['status', 'level', 'page', 'limit']);

        try {
            $users = UserResource::collection(
                $this->userService->list($parameters)
            );

            return new SuccessResponse('item.success.user.findMany', [
                'users' => $users,
            ]);
        } catch (Throwable $exception) {
            return new ExceptionErrorResponse('item.error.user.findMany', $exception);
        }
    }

    public function show(int $userId): JsonResponse
    {
        try {
            $user = $this->userService->getById($userId);

            return new SuccessResponse('item.success.user.findOne', [
                'user' => new UserResource($user)
            ], Response::HTTP_OK);
        } catch (Throwable $exception) {
            // Bugsnag::notifyException($exception);

            return new ExceptionErrorResponse('item.error.user.findOne', $exception);
        }
    }

    public function update(UpdateUserRequest $request, int $userId): JsonResponse
    {
        /**
         * @var User $authenticatedUser;
         */
        $authenticatedUser = Auth::user();
        $input = $request->only(['username']);

        try {
            $updatedUser = $this->userService->update($userId, $input, $authenticatedUser);

            return new SuccessResponse('item.success.user.updateOne', [
                'user' => new UserResource($updatedUser)
            ]);
        } catch (Throwable $exception) {
            // Bugsnag::notifyException($exception);

            return new ExceptionErrorResponse('item.error.user.findOne', $exception);
        }
    }

    public function activate(int $userId): JsonResponse
    {
        /**
         * @var User $authenticatedUser;
         */
        $authenticatedUser = Auth::user();

        try {
            $activatedUser = $this->userService->activate($userId, $authenticatedUser);

            return new SuccessResponse('item.success.user.publishOne', [
                'user' => new UserResource($activatedUser)
            ], Response::HTTP_OK);
        } catch (Throwable $exception) {
            // Bugsnag::notifyException($exception);

            return new ExceptionErrorResponse('item.error.user.updateOne', $exception);
        }
    }

    public function deactivate(int $userId): JsonResponse
    {
        /**
         * @var User $authenticatedUser;
         */
        $authenticatedUser = Auth::user();

        try {
            $deactivatedUser = $this->userService->deactivate($userId, $authenticatedUser);

            return new SuccessResponse('item.success.user.unpublishOne', [
                'user' => new UserResource($deactivatedUser)
            ], Response::HTTP_OK);
        } catch (Throwable $exception) {
            // Bugsnag::notifyException($exception);

            return new ExceptionErrorResponse('item.failed.user.updateOne', $exception);
        }
    }
}
