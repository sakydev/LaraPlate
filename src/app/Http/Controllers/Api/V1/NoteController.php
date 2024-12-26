<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Requests\Api\V1\Notes\CreateNoteRequest;
use App\Requests\Api\V1\Notes\UpdateNoteRequest;
use App\Resources\Api\V1\NoteResource;
use App\Resources\Api\V1\Responses\ExceptionErrorResponse;
use App\Resources\Api\V1\Responses\SuccessResponse;
use App\Services\Notes\NoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class NoteController extends Controller
{
    public function __construct(
        private readonly NoteService $noteService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $parameters = $request->only(['status', 'page', 'limit']);

        try {
            $users = NoteResource::collection(
                $this->noteService->list($parameters)
            );

            return new SuccessResponse('item.success.note.findMany', [
                'notes' => $users,
            ]);
        } catch (Throwable $exception) {
            return new ExceptionErrorResponse('item.error.note.findMany', $exception);
        }
    }

    public function show(int $noteId): JsonResponse
    {
        try {
            $user = $this->noteService->getById($noteId);

            return new SuccessResponse('item.success.note.findOne', [
                'note' => new NoteResource($user)
            ], Response::HTTP_OK);
        } catch (Throwable $exception) {
            // Bugsnag::notifyException($exception);

            return new ExceptionErrorResponse('item.error.note.findOne', $exception);
        }
    }

    public function store(CreateNoteRequest $createNoteRequest): JsonResponse
    {
        /**
         * @var User $authenticatedUser;
         */
        $authenticatedUser = Auth::user();
        $input = $createNoteRequest->only(['name', 'content', 'status']);

        try {
            $createdNote = $this->noteService->create($input, $authenticatedUser);

            return new SuccessResponse('item.success.note.createOne', [
                'note' => new NoteResource($createdNote)
            ], Response::HTTP_CREATED);
        } catch (Throwable $exception) {
            // Bugsnag::notifyException($exception);

            return new ExceptionErrorResponse('item.error.note.createOne', $exception);
        }
    }

    public function update(UpdateNoteRequest $request, int $noteId): JsonResponse
    {
        /**
         * @var User $authenticatedUser;
         */
        $authenticatedUser = Auth::user();
        $input = $request->only(['name', 'content']);

        try {
            $updatedNote = $this->noteService->update($noteId, $input, $authenticatedUser);

            return new SuccessResponse('item.success.note.updateOne', [
                'note' => new NoteResource($updatedNote)
            ]);
        } catch (Throwable $exception) {
            // Bugsnag::notifyException($exception);

            return new ExceptionErrorResponse('item.error.note.findOne', $exception);
        }
    }

    public function publish(int $noteId): JsonResponse
    {
        /**
         * @var User $authenticatedUser;
         */
        $authenticatedUser = Auth::user();

        try {
            $activatedUser = $this->noteService->publish($noteId, $authenticatedUser);

            return new SuccessResponse('item.success.note.publishOne', [
                'note' => new NoteResource($activatedUser)
            ], Response::HTTP_OK);
        } catch (Throwable $exception) {
            // Bugsnag::notifyException($exception);

            return new ExceptionErrorResponse('item.error.note.updateOne', $exception);
        }
    }

    public function draft(int $noteId): JsonResponse
    {
        /**
         * @var User $authenticatedUser;
         */
        $authenticatedUser = Auth::user();

        try {
            $deactivatedUser = $this->noteService->draft($noteId, $authenticatedUser);

            return new SuccessResponse('item.success.note.unpublishOne', [
                'note' => new NoteResource($deactivatedUser)
            ], Response::HTTP_OK);
        } catch (Throwable $exception) {
            // Bugsnag::notifyException($exception);

            return new ExceptionErrorResponse('item.failed.note.updateOne', $exception);
        }
    }

    public function delete(int $noteId): JsonResponse
    {
        /**
         * @var User $authenticatedUser;
         */
        $authenticatedUser = Auth::user();

        try {
            $this->noteService->delete($noteId, $authenticatedUser);

            return new SuccessResponse(
                'item.success.note.deleteOne',
                [],
                Response::HTTP_NO_CONTENT,
            );
        } catch (Throwable $exception) {
            // Bugsnag::notifyException($exception);

            return new ExceptionErrorResponse('item.failed.note.deleteOne', $exception);
        }
    }
}
