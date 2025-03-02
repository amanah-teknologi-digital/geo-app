<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengajuanGeoFacilityController;
use App\Http\Controllers\PengajuanGeoLetterController;
use App\Http\Controllers\PengajuanGeoRoomController;
use App\Http\Controllers\PeralatanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RuanganController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing_page.index');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware(['role:8'])->group(function () {
        Route::get('/dashboard')->middleware('defaultdashboard')->name('dashboard');
        Route::get('/dashboard-pengguna', [DashboardController::class, 'pengguna'])->name('dashboard.pengguna');

        //pengajuan
        Route::get('/pengajuan-geoletter', [PengajuanGeoLetterController::class, 'index'])->name('pengajuangeoletter.index');
        Route::get('/pengajuan-georoom', [PengajuanGeoRoomController::class, 'index'])->name('pengajuangeoroom.index');
        Route::get('/pengajuan-geofacility', [PengajuanGeoFacilityController::class, 'index'])->name('pengajuangeofacility.index');

        //ruangan
        Route::get('/ruangan', [RuanganController::class, 'index'])->name('ruangan.index');

        //peralatan
        Route::get('/peralatan', [PeralatanController::class, 'index'])->name('peralatan.index');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
