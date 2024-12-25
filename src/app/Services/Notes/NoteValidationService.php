<?php

namespace App\Services\Notes;

use App\Exceptions\ForbiddenException;
use App\Models\Note;
use App\Models\User;
use App\Services\ValidationService;

class NoteValidationService extends ValidationService
{
    /**
     * @throws ForbiddenException
     */
    public function validateCanUpdate(Note $requestedNote, User $authenticatedUser): void
    {
        if (!$authenticatedUser->isAdmin()
            && $requestedNote->user_id != $authenticatedUser->getAuthIdentifier()
        ) {
            throw new ForbiddenException('item.error.generic.invalidUpdatePermissions');
        }
    }

    /**
     * @throws ForbiddenException
     */
    public function validateCanPublish(Note $requestedNote, User $authenticatedUser): void
    {
        if (!$authenticatedUser->isAdmin()
            || $requestedNote->user_id === $authenticatedUser->getAuthIdentifier()
        ) {
            throw new ForbiddenException('item.error.generic.invalidUpdatePermissions');
        }
    }

    /**
     * @throws ForbiddenException
     */
    public function validatePreConditionsToUpdate(Note $requestedNote, User $authenticatedUser): void
    {
        $this->validateCanUpdate($requestedNote, $authenticatedUser);
    }

    /**
     * @throws ForbiddenException
     */
    public function validatePreConditionsToPublish(Note $requestedNote, User $authenticatedUser): void
    {
        $this->validateCanPublish($requestedNote, $authenticatedUser);
    }

    /**
     * @throws ForbiddenException
     */
    public function validatePreConditionsToDraft(Note $requestedNote, User $authenticatedUser): void
    {
        $this->validateCanPublish($requestedNote, $authenticatedUser);
    }
}
