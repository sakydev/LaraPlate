<?php

namespace App\Services\Users;

use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Exceptions\UnprocessableException;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Collection;

class UserService
{
    public function __construct(
        private readonly UserValidationService $userValidationService,
        private readonly UserRepository $userRepository,
    ) {
    }

    /**
     * @throws UnprocessableException
     */
    public function create(array $input): User
    {
        return $this->userRepository->create($input);
    }

    /**
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function getById(int $userId): User
    {
        $user = $this->userRepository->get($userId);
        if (!$user) {
            throw new NotFoundException('user.failed.findOne');
        }

        return $user;
    }

    /**
     * @throws ForbiddenException
     */
    public function list(array $parameters): Collection
    {
        return $this->userRepository->list(
            $parameters,
            $parameters['page'] ?? 1,
            $parameters['limit'] ?? 10
        );
    }

    /**
     * @throws NotFoundException
     * @throws ForbiddenException
     */
    public function update(int $userId, array $input, User $authenticatedUser): User
    {
        $requestedUser = $this->getById($userId);
        $this->userValidationService->validatePreConditionsToUpdate($requestedUser, $authenticatedUser);

        return $this->userRepository->update($requestedUser, $input);
    }

    /**
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function activate(int $userId, User $authenticatedUser): User
    {
        $requestedUser = $this->getById($userId);
        $this->userValidationService->validatePreConditionsToActivate($requestedUser, $authenticatedUser);

        return $this->userRepository->publish($requestedUser);
    }

    /**
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function deactivate(int $userId, User $authenticatedUser): User
    {
        $requestedUser = $this->getById($userId);
        $this->userValidationService->validatePreConditionsToDeactivate($requestedUser, $authenticatedUser);

        return $this->userRepository->draft($requestedUser);
    }
}
