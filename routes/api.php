<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EnumController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/auth')->group(function () {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('/users')->name('users.')->group(function () {
        Route::get('/me', [UserController::class, 'show'])->name('me');
        Route::patch('/me/update-password', [UserController::class, 'updatePassword'])->name('update_password');
    });

    Route::prefix('/profiles')->name('profiles.')->group(function () {
        Route::get('/search', [ProfileController::class, 'search'])->name('search');
        Route::get('/{user}', [ProfileController::class, 'show'])->name('show');
        Route::put('/{user}/update', [ProfileController::class, 'update'])->name('update');
    });

    Route::prefix('/friends')->name('friends.')->group(function () {
        Route::post('/send-request/{receiverId}', [FriendController::class, 'send'])->name('send_request');
        Route::post('/accept-request/{requestId}', [FriendController::class, 'accept'])->name('accept_request');
        Route::post('/reject-request/{requestId}', [FriendController::class, 'reject'])->name('reject_request');
        Route::get('/list', [FriendController::class, 'list'])->name('list')->name('list');
        Route::get('/requests', [FriendController::class, 'requests'])->name('requests');

    });

    Route::prefix('/enums')->name('enums.')->group(function () {
        Route::get('/travel-preferences', [EnumController::class, 'getTravelPreferences'])->name('travel_preferences');
        Route::get('/travel-categories', [EnumController::class, 'getTravelCategories'])->name('travel_categories');
    });

});



