<?php

namespace App\Services\Notes;

use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Exceptions\UnprocessableException;
use App\Models\Note;
use App\Models\User;
use App\Repositories\NoteRepository;
use Illuminate\Database\Eloquent\Collection;

class NoteService
{
    public function __construct(
        private readonly NoteValidationService $noteValidationService,
        private readonly NoteRepository $noteRepository,
    ) {
    }

    /**
     * @param array<string, mixed> $input
     *
     * @throws UnprocessableException
     */
    public function create(array $input, User $authenticatedUser): Note
    {
        return $this->noteRepository->create($input, $authenticatedUser);
    }

    /**
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function getById(int $noteId): Note
    {
        $note = $this->noteRepository->get($noteId);
        if (!$note) {
            throw new NotFoundException('item.error.note.findOne');
        }

        return $note;
    }

    /**
     * @param array<string, mixed> $parameters
     * @return Collection<int, Note>
     *
     * @throws ForbiddenException
     */
    public function list(array $parameters): Collection
    {
        return $this->noteRepository->list(
            $parameters,
            $parameters['page'] ?? 1,
            $parameters['limit'] ?? 10
        );
    }

    /**
     * @param array<string, mixed> $input
     *
     * @throws NotFoundException
     * @throws ForbiddenException
     */
    public function update(int $noteId, array $input, User $authenticatedUser): Note
    {
        $requestedNote = $this->getById($noteId);
        $this->noteValidationService->validatePreConditionsToUpdate($requestedNote, $authenticatedUser);

        return $this->noteRepository->update($requestedNote, $input);
    }

    /**
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function publish(int $noteId, User $authenticatedUser): Note
    {
        $requestedNote = $this->getById($noteId);
        $this->noteValidationService->validatePreConditionsToPublish($requestedNote, $authenticatedUser);

        return $this->noteRepository->publish($requestedNote);
    }

    /**
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function draft(int $noteId, User $authenticatedUser): Note
    {
        $requestedNote = $this->getById($noteId);
        $this->noteValidationService->validatePreConditionsToDraft($requestedNote, $authenticatedUser);

        return $this->noteRepository->draft($requestedNote);
    }

    /**
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function delete(int $noteId, User $authenticatedUser): void
    {
        $requestedNote = $this->getById($noteId);
        $this->noteValidationService->validatePreConditionsToUpdate($requestedNote, $authenticatedUser);

        $this->noteRepository->delete($requestedNote);
    }
}
