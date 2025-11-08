<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RolController;
use App\Http\Controllers\Api\PermisoController;
use App\Http\Controllers\Api\ComiteController;
use App\Http\Controllers\Api\ReunionController;
use App\Http\Controllers\Api\IndicadorController;
use App\Http\Controllers\Api\IndicadorValorController;

Route::middleware('throttle:10,1')->prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::get('/ping', fn () => response()->json(['pong' => true]));

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('usuarios', UserController::class);
    Route::apiResource('roles', RolController::class);
    Route::apiResource('permisos', PermisoController::class);
    Route::apiResource('comites', ComiteController::class);
    Route::apiResource('reuniones', ReunionController::class);
    Route::apiResource('indicadores', IndicadorController::class);
    Route::apiResource('indicador-valores', IndicadorValorController::class);
});
