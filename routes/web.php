<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\PengajuanGeoFacilityController;
use App\Http\Controllers\PengajuanGeoLetterController;
use App\Http\Controllers\PengajuanGeoRoomController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\PeralatanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RuanganController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index'])->name('landingpage');
Route::get('/get-public-file/{id_file}', [FileController::class, 'getPublicFile'])->middleware('nocache')->name('file.getpublicfile');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard')->middleware('defaultdashboard')->name('dashboard');
    Route::get('/dashboard-pengguna', [DashboardController::class, 'pengguna'])->middleware('role:8')->name('dashboard.pengguna');
    Route::get('/dashboard-letter', [DashboardController::class, 'letter'])->middleware('role:1,2,5,6,7')->name('dashboard.letter');
    Route::get('/dashboard-room', [DashboardController::class, 'room'])->middleware('role:1,3,5,6,7')->name('dashboard.room');
    Route::get('/dashboard-facility', [DashboardController::class, 'facility'])->middleware('role:1,4,5,6,7')->name('dashboard.facility');
    //ruangan
    Route::get('/ruangan', [RuanganController::class, 'index'])->name('ruangan');

    //peralatan
    Route::get('/peralatan', [PeralatanController::class, 'index'])->name('peralatan');

    //pengumuman
    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman');
    Route::get('/pengumuman/tambah', [PengumumanController::class, 'tambahPengumuman'])->name('pengumuman.tambah');
    Route::post('/pengumuman/dotambah', [PengumumanController::class, 'dotambahPengumuman'])->name('pengumuman.dotambah');
    Route::get('/pengumuman/edit/{id_pengumuman}', [PengumumanController::class, 'editPengumuman'])->name('pengumuman.edit');
    Route::post('/pengumuman/doedit', [PengumumanController::class, 'doeditPengumuman'])->name('pengumuman.doedit');
    Route::get('/pengumuman/getdata', [PengumumanController::class, 'getData'])->name('pengumuman.getdata');
    Route::post('/pengumuman/hapus', [PengumumanController::class, 'hapusPengumuman'])->name('pengumuman.hapus');
    Route::post('/pengumuman/posting', [PengumumanController::class, 'postingPengumuman'])->name('pengumuman.posting');
    Route::post('/pengumuman/unposting', [PengumumanController::class, 'batalPostingPengumuman'])->name('pengumuman.unposting');

    //pengaturan
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan');
    Route::post('/pengaturan/update', [PengaturanController::class, 'updatePengaturan'])->name('pengaturan.update');

    //pengajuan
    Route::get('/pengajuan-geoletter', [PengajuanGeoLetterController::class, 'index'])->middleware('role:1,2,8')->name('pengajuangeoletter');
    Route::get('/pengajuan-georoom', [PengajuanGeoRoomController::class, 'index'])->middleware('role:1,3,6,7,8')->name('pengajuangeoroom');
    Route::get('/pengajuan-geofacility', [PengajuanGeoFacilityController::class, 'index'])->middleware('role:1,4,5,7,8')->name('pengajuangeofacility');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/get-private-file/{id_file}', [FileController::class, 'getPrivateFile'])->middleware('nocache')->name('file.getprivatefile');
});

require __DIR__.'/auth.php';
