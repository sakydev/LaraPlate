<?php

namespace App\Services\Users;

use App\Exceptions\ForbiddenException;
use App\Models\User;

class UserValidationService
{
    /**
     * @throws ForbiddenException
     */
    public function validateCanUpdate(User $requestedUser, User $authenticatedUser): void
    {
        if (
            !$authenticatedUser->isAdmin()
            && $requestedUser->id != $authenticatedUser->getAuthIdentifier()
        ) {
            throw new ForbiddenException('item.error.generic.invalidUpdatePermissions');
        }
    }

    /**
     * @throws ForbiddenException
     */
    public function validateCanActivate(User $requestedUser, User $authenticatedUser): void
    {
        if (
            !$authenticatedUser->isAdmin()
            || $requestedUser->id === $authenticatedUser->getAuthIdentifier()
        ) {
            throw new ForbiddenException('item.error.generic.invalidUpdatePermissions');
        }
    }

    /**
     * @throws ForbiddenException
     */
    public function validatePreConditionsToUpdate(User $requestedUser, User $authenticatedUser): void
    {
        $this->validateCanUpdate($requestedUser, $authenticatedUser);
    }

    /**
     * @throws ForbiddenException
     */
    public function validatePreConditionsToActivate(User $requestedUser, User $authenticatedUser): void
    {
        $this->validateCanActivate($requestedUser, $authenticatedUser);
    }

    /**
     * @throws ForbiddenException
     */
    public function validatePreConditionsToDeactivate(User $requestedUser, User $authenticatedUser): void
    {
        $this->validateCanActivate($requestedUser, $authenticatedUser);
    }
}
