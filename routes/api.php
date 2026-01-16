<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\User\{
    IndexController as UserIndexController,
    StoreController as UserStoreController,
    ShowController as UserShowController,
    UpdateController as UserUpdateController,
    DestroyController as UserDestroyController
};
use App\Http\Controllers\Api\Address\{
    IndexController as AddressIndexController,
    StoreController as AddressStoreController,
    ShowController as AddressShowController,
    UpdateController as AddressUpdateController,
    DestroyController as AddressDestroyController
};
use App\Http\Controllers\Api\Permission\{
    StoreController as PermissionStoreController,
    IndexController as PermissionIndexController,
    DestroyController as PermissionDestroyController
};

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/users', UserStoreController::class);

    Route::middleware('auth:api')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/users', UserIndexController::class);
        Route::get('/users/{id}', UserShowController::class);
        Route::put('/users/{id}', UserUpdateController::class);
        Route::delete('/users/{id}', UserDestroyController::class);

        Route::get('/users/{user}/addresses', AddressIndexController::class);
        Route::post('/users/{user}/addresses', AddressStoreController::class);
        Route::get('/users/{user}/addresses/{address}', AddressShowController::class);
        Route::put('/users/{user}/addresses/{address}', AddressUpdateController::class);
        Route::delete('/users/{user}/addresses/{address}', AddressDestroyController::class);

        Route::post('/users/{user}/permissions', PermissionStoreController::class);
        Route::get('/users/{user}/permissions', PermissionIndexController::class);
        Route::delete('/users/{user}/permissions/{permission}', PermissionDestroyController::class);
    });
});


