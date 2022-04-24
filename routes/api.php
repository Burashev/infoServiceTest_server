<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApplicationController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth.api')->group(function () {
    Route::prefix('/email')->group(function() {
        Route::get('/verify', [AuthController::class, 'verificationNotice'])
            ->name('verification.notice');

        Route::get('/verify/{id}/{hash}', [AuthController::class, 'verificationVerify'])
            ->middleware('signed')->name('verification.verify');

        Route::post('/verification-notification', [AuthController::class, 'verificationSend'])
            ->middleware('throttle:6,1')->name('verification.send');
    });

    Route::prefix('/application')->group(function() {
        Route::post('/create', [ApplicationController::class, 'createApplication']);

    });
});
