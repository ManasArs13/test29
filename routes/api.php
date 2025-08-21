<?php

use App\Http\Controllers\Api\Auth\AuthController;

use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\CarModelController;
use Illuminate\Support\Facades\Route;

Route::get('brands', [BrandController::class, 'index']);
Route::get('car-models', [CarModelController::class, 'index']);

Route::post('/register', [AuthController::class, 'register']);

Route::group(['middleware' => 'api'], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('user', [AuthController::class, 'user']);

    Route::apiResource('cars', CarController::class);
});
