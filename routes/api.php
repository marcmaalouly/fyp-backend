<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PositionController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('positions', [PositionController::class, 'index']);
    Route::group(['prefix' => 'position/'], function () {
        Route::post('', [PositionController::class, 'store']);
        Route::put('{position}', [PositionController::class, 'update']);
        Route::delete('{position}', [PositionController::class, 'destroy']);
    });
});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
