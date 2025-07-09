<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\PassengerController;
use App\Http\Controllers\ExportUserController;
use App\Http\Controllers\FlightImportController;
use App\Http\Controllers\PassengerImageController;

// Public routes (no auth required)
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::middleware('cache.response')->group(function () {
        Route::apiResource('passengers', PassengerController::class)->only(['index', 'show']);
        Route::post('/passengers/{id}/upload-image', [PassengerImageController::class, 'upload']);
    });
    Route::apiResource('flights', FlightController::class)->only(['index', 'show']);
    Route::apiResource('users', UserController::class)->only(['index', 'show']);

    Route::middleware(['role:admin'])->group(function () {
        Route::apiResource('users', UserController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('flights', FlightController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('passengers', PassengerController::class)->only(['store', 'update', 'destroy']);
        Route::get('/export-users', [ExportUserController::class, 'exportUsers']);
        Route::post('/import-flights', [FlightImportController::class, 'import']);
    });
});
