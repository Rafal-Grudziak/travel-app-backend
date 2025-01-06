<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EnumController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TravelController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/auth')->group(function () {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('/users')->group(function () {
        Route::get('/me', [UserController::class, 'show']);
        Route::patch('/me/update-password', [UserController::class, 'updatePassword']);
    });

    Route::prefix('/profiles')->group(function () {
        Route::get('/search', [ProfileController::class, 'search']);
        Route::get('/{user}', [ProfileController::class, 'show']);
        Route::put('/{user}/update', [ProfileController::class, 'update']);
    });

    Route::prefix('/friends')->group(function () {
        Route::get('/list', [FriendController::class, 'list'])->name('list');
        Route::get('/requests', [FriendController::class, 'requests']);
        Route::post('/send-request', [FriendController::class, 'send']);
        Route::delete('/delete/{friendId}', [FriendController::class, 'delete']);
        Route::patch('/accept-request/{requestId}', [FriendController::class, 'accept']);
        Route::delete('/reject-request/{requestId}', [FriendController::class, 'reject']);
    });

    Route::prefix('/enums')->group(function () {
        Route::get('/travel-preferences', [EnumController::class, 'getTravelPreferences']);
        Route::get('/travel-categories', [EnumController::class, 'getTravelCategories']);
    });

    Route::prefix('/travels')->group(function () {
        Route::get('/', [TravelController::class, 'index']);
        Route::post('/', [TravelController::class, 'store']);
        Route::get('/{travel}', [TravelController::class, 'show']);
        Route::get('/user/{user}', [TravelController::class, 'usersTravels']);
        Route::put('/{travel}/update', [TravelController::class, 'update']);
        Route::delete('/{travel}/delete', [TravelController::class, 'destroy']);
        Route::patch('/{travel}/toggle-favourite', [TravelController::class, 'toggleFavourite']);
    });

});



