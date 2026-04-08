<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TicketApiController;
use App\Http\Controllers\Api\AuthApiController;

// Auth API
Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/forgot-password', [AuthApiController::class, 'forgotPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tickets', [TicketApiController::class, 'index']);
    Route::post('/tickets', [TicketApiController::class, 'store']);
    Route::get('/tickets/{ticket}', [TicketApiController::class, 'show']);
    Route::post('/logout', [AuthApiController::class, 'logout']);
});