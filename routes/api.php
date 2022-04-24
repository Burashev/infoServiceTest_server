<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth.api')->group(function () {
    Route::get('/email/verify', [AuthController::class, 'verificationNotice'])
        ->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verificationVerify'])
        ->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', [AuthController::class, 'verificationSend'])
        ->middleware('throttle:6,1')->name('verification.send');

});
