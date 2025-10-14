<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\WidgetController;
use Illuminate\Support\Facades\Route;

// Public endpoints
Route::post('/login', [AuthController::class, 'login']);
Route::post('/widget/submit', [WidgetController::class, 'submit']);

// Protected API routes
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Customers
    Route::apiResource('customers', CustomerController::class);

    // Tickets
    Route::apiResource('tickets', TicketController::class);
    Route::post('tickets/{ticket}/change-status', [TicketController::class, 'changeStatus']);

    // Statistics
    Route::get('statistics', [StatisticsController::class, 'index'])
        ->middleware('permission:view statistics');
    Route::get('statistics/managers', [StatisticsController::class, 'managerStats'])
        ->middleware('permission:view statistics');
});
