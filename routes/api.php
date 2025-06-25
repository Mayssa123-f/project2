<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\PassengerController;

// Public routes (no auth required)
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/passengers', [PassengerController::class, 'index']);
    Route::get('/flights', [FlightController::class, 'index']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/export-users', [UserController::class, 'exportUsers']);
    Route::middleware(['role:admin'])->group(function(){
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);

    Route::post('/flights', [FlightController::class, 'store']);
    Route::put('/flights/{flight}', [FlightController::class, 'update']);
    Route::delete('/flights/{flight}', [FlightController::class, 'destroy']);
    Route::get('/flights/{id}/passengers', [FlightController::class, 'getPassengers']);

    Route::post('/passengers', [PassengerController::class, 'store']);
    Route::put('/passengers/{passenger}', [PassengerController::class, 'update']);
    Route::delete('/passengers/{passenger}', [PassengerController::class, 'destroy']);


    Route::middleware(['throttle:api'])->get('/test', function () {
    return response()->json(['message' => 'OK']);
});
    Route::get('/testCache',function(){
        Cache::put('test','hello mayssaa',5);
        $value=Cache::get('test');
        $exist=Cache::has('test');
        Cache::forget('test');
           $stillExists = Cache::has('test');

            return response()->json([
            'put'=>'test=hello mayssa',
            'has'=>$exist,
            'get'=>$value,
            'still exist'=>$stillExists
            ]);
    });
});
 

});
