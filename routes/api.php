<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MailConnectionController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\SkillKeyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZoomMeetingController;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('/oauth/gmail', function (){
        return LaravelGmail::redirect();
    });

    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    Route::get('dashboards', [DashboardController::class, 'index']);

    Route::get('menu', [MenuController::class, 'index']);

    Route::get('favorites', [UserController::class, 'favorites']);
    Route::post('favorite/{candidate}', [UserController::class, 'storeFavorite']);

    Route::post('zoom-connect', [UserController::class, 'connectToZoom']);
    Route::get('zoom-check', [UserController::class, 'checkIfConnectedToZoom']);
    Route::post('zoom-meeting/{candidate}', [ZoomMeetingController::class, 'store']);
    Route::get('zoom-meeting/{candidate}', [ZoomMeetingController::class, 'create']);
    Route::get('zoom-meetings', [ZoomMeetingController::class, 'index']);
    Route::delete('zoom-meeting/{meeting_id}/delete', [ZoomMeetingController::class, 'destroy']);
    Route::get('zoom-meeting/{meeting}/view', [ZoomMeetingController::class, 'show']);

    Route::get('email-templates', [EmailTemplateController::class, 'index']);
    Route::post('email-template', [EmailTemplateController::class, 'store']);
    Route::delete('email-template/{template}', [EmailTemplateController::class, 'destroy']);
    Route::put('email-template/{template}', [EmailTemplateController::class, 'update']);

    Route::get('skills', [SkillKeyController::class, 'index']);

    Route::get('positions', [PositionController::class, 'index']);
    Route::group(['prefix' => 'position/'], function () {
        Route::post('', [PositionController::class, 'store']);
        Route::put('{position}', [PositionController::class, 'update']);
        Route::delete('{position}', [PositionController::class, 'destroy']);

        Route::group(['prefix' => '{position}/', 'middleware' => 'user_owner_position'], function () {
            Route::get('languages', [LanguageController::class, 'index']);

            Route::group(['prefix' => 'language/'], function () {
                Route::post('', [LanguageController::class, 'store']);

                Route::group(['prefix' => '{language}/', 'middleware' => 'language_belongs_to_position'], function () {
                    Route::put('update', [LanguageController::class, 'update']);
                    Route::post('connect', [MailConnectionController::class, 'connect']);
                    Route::post('mails', [MailConnectionController::class, 'fetch']);
                    Route::post('mails/email', [MailConnectionController::class, 'fetchByEmail']);
                    Route::post('skills', [LanguageController::class, 'storeSkills']);
                    Route::delete('', [LanguageController::class, 'destroy']);

                    Route::get('candidates', [CandidateController::class, 'index']);
                });
            });
        });
    });
});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('check-email', [AuthController::class, 'checkEmail']);
Route::post('verify-email', [AuthController::class, 'verifyEmail']);
