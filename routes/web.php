<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing_page.index');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware(['role:8'])->group(function () {
        Route::get('/dashboard')->middleware('defaultdashboard')->name('dashboard');
        Route::get('/dashboard-pengguna', [DashboardController::class, 'pengguna'])->name('dashboard.pengguna');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
