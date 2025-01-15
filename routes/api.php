<?php

use App\Http\Controllers\Api\ThreadController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/users', [UserController::class, 'register']);
Route::post('/authentications', [UserController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::resource('threads', ThreadController::class)->except(['index', 'show']);
});

Route::prefix('threads')->group(function () {
    Route::get('/', [ThreadController::class, 'index']);
    Route::get('/{threadId}', [ThreadController::class, 'show']);
});
