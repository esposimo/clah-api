<?php

declare(strict_types=1);

use App\Http\Controllers\EnvironmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('env')->group(function (): void {
    Route::get('/items', [EnvironmentController::class, 'list']);
    Route::post('/items', [EnvironmentController::class, 'list']);
    Route::get('/items/{id}', [EnvironmentController::class, 'show']);
    Route::put('/items/{id}', [EnvironmentController::class, 'list']);
    Route::delete('/items/{id}', [EnvironmentController::class, 'list']);
});


Route::fallback(function (Request $request) {
    return response()->json([
        'message' => sprintf('Route not found: %s %s', $request->method(), $request->path()),
    ], 404);
});

