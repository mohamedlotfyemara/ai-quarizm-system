<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard.login'));

Route::get('/login', [DashboardController::class, 'loginForm'])->name('dashboard.login');
Route::post('/login', [DashboardController::class, 'login'])->name('dashboard.login.submit');
Route::post('/logout', [DashboardController::class, 'logout'])->name('dashboard.logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/tickets/{ticket}', [DashboardController::class, 'show'])->name('dashboard.tickets.show');
    Route::post('/dashboard/customers', [DashboardController::class, 'storeCustomer'])->name('dashboard.customers.store');
    Route::post('/dashboard/staff', [DashboardController::class, 'storeStaff'])->name('dashboard.staff.store');
});
