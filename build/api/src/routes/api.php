<?php

declare(strict_types=1);

use App\Http\Controllers\Api\EnvItemController;
use App\Http\Controllers\Api\NetworkItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('env')->group(function (): void {
    Route::get('/items', [EnvItemController::class, 'index']);
    Route::post('/items', [EnvItemController::class, 'store']);
    Route::get('/items/{id}', [EnvItemController::class, 'show']);
    Route::put('/items/{id}', [EnvItemController::class, 'update']);
    Route::delete('/items/{id}', [EnvItemController::class, 'destroy']);
});

Route::prefix('networks/{envId}')->group(function (): void {
    Route::get('/items', [NetworkItemController::class, 'index']);
    Route::post('/items', [NetworkItemController::class, 'store']);
    Route::get('/{id}', [NetworkItemController::class, 'show']);
    Route::put('/{id}', [NetworkItemController::class, 'update']);
    Route::delete('/{id}', [NetworkItemController::class, 'destroy']);
});

Route::fallback(function (Request $request) {
    return response()->json([
        'message' => sprintf('Route not found: %s %s', $request->method(), $request->path()),
    ], 404);
});
