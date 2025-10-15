<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// Login routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Protected admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Ticket routes
    Route::resource('tickets', \App\Http\Controllers\Admin\TicketController::class)->except(['create', 'store', 'destroy']);
    Route::post('tickets/{ticket}/change-status', [\App\Http\Controllers\Admin\TicketController::class, 'changeStatus'])->name('tickets.change-status');

    // Customer routes
    Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class)->only(['index', 'show']);

    // User routes
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->only(['index', 'show']);
});
