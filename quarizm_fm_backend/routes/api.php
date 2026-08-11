<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketController;
use Illuminate\Support\Facades\Route;

// عام
Route::post('/login', [AuthController::class, 'login']);

// محمي بتوكن Sanctum - يُستخدم من تطبيق Flutter
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/tickets', [TicketController::class, 'index']);
    Route::post('/tickets', [TicketController::class, 'store']);
    Route::get('/tickets/{ticket}', [TicketController::class, 'show']);
    Route::patch('/tickets/{ticket}/assign', [TicketController::class, 'assign']);
    Route::patch('/tickets/{ticket}/accept', [TicketController::class, 'accept']);
    Route::patch('/tickets/{ticket}/start', [TicketController::class, 'start']);
    Route::post('/tickets/{ticket}/report', [TicketController::class, 'submitReport']);
    Route::patch('/tickets/{ticket}/confirm', [TicketController::class, 'confirm']);

    Route::get('/stats', [TicketController::class, 'stats']);
});
