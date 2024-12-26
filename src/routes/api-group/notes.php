<?php declare(strict_types=1);

use App\Http\Controllers\Api\V1\NoteController;
use Illuminate\Support\Facades\Route;

Route::prefix('notes')->middleware('auth:sanctum')->group(function (): void {
    Route::get('/', [NoteController::class, 'index'])->name('notes.list');
    Route::get('/{noteId}', [NoteController::class, 'show'])->name('notes.show');
    Route::post('/', [NoteController::class, 'store'])->name('notes.store');
    Route::put('/{noteId}', [NoteController::class, 'update'])->name('notes.update');
    Route::delete('/{noteId}', [NoteController::class, 'delete'])->name('notes.delete');
    Route::put('/{noteId}/publish', [NoteController::class, 'publish'])
        ->name('notes.publish');
    Route::put('/{noteId}/draft', [NoteController::class, 'draft'])
        ->name('notes.draft');
});
