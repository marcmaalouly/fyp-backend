<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MailConnectionController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\SkillKeyController;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('/oauth/gmail', function (){
        return LaravelGmail::redirect();
    });

    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    Route::get('menu', [MenuController::class, 'index']);

    Route::get('skills', [SkillKeyController::class, 'index']);

    Route::get('positions', [PositionController::class, 'index']);
    Route::group(['prefix' => 'position/'], function () {
        Route::post('', [PositionController::class, 'store']);
        Route::put('{position}', [PositionController::class, 'update']);
        Route::delete('{position}', [PositionController::class, 'destroy']);

        Route::group(['prefix' => '{position}/', 'middleware' => 'user_owner_position'], function () {
            Route::get('languages', [LanguageController::class, 'index']);
            Route::group(['prefix' => 'language/'], function () {
                Route::post('{language}/connect', [MailConnectionController::class, 'connect']);
                Route::post('{language}/mails', [MailConnectionController::class, 'fetch']);
                Route::post('{language}/mails/email', [MailConnectionController::class, 'fetchByEmail']);
                Route::post('', [LanguageController::class, 'store']);
                Route::delete('{language}', [LanguageController::class, 'destroy'])->middleware('language_belongs_to_position');
            });
        });
    });
});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('check-email', [AuthController::class, 'checkEmail']);
Route::post('verify-email', [AuthController::class, 'verifyEmail']);
