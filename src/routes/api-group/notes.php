<?php declare(strict_types=1);

use App\Http\Controllers\Api\V1\NoteController;
use Illuminate\Support\Facades\Route;

Route::prefix('notes')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [NoteController::class, 'index'])->name('notes.list');
    Route::get('/{userId}', [NoteController::class, 'show'])->name('notes.show');
    Route::put('/{userId}', [NoteController::class, 'update'])->name('notes.update');
    Route::delete('/{userId}', [NoteController::class, 'delete'])->name('notes.delete');
    Route::put('/{userId}/publish', [NoteController::class, 'publish'])
        ->name('notes.publish');
    Route::put('/{userId}/draft', [NoteController::class, 'draft'])
        ->name('notes.draft');
});
