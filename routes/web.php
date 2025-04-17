<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\PengajuanGeoFacilityController;
use App\Http\Controllers\PengajuanPersuratanController;
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
Route::get('/pengumuman/lihat/{id_pengumuman}', [LandingPageController::class, 'lihatPengumuman'])->name('pengumuman.lihatpengumuman');
Route::get('/pengumuman/list', [LandingPageController::class, 'listPengumuman'])->name('pengumuman.listpengumuman');
Route::get('/pengumuman/getlist', [LandingPageController::class, 'getListPengumuman'])->name('pengumuman.getlistpengumuman');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard')->middleware('defaultdashboard')->name('dashboard');
    Route::get('/dashboard-surat', [DashboardController::class, 'surat'])->middleware('role:1,2,5,6,7')->name('dashboard.surat');
    Route::get('/dashboard-ruangan', [DashboardController::class, 'ruangan'])->middleware('role:1,3,5,6,7')->name('dashboard.ruangan');
    Route::get('/dashboard-peralatan', [DashboardController::class, 'peralatan'])->middleware('role:1,4,5,6,7')->name('dashboard.peralatan');
    Route::get('/dashboard-pengguna', [DashboardController::class, 'pengguna'])->middleware('role:8')->name('dashboard.pengguna');

    Route::middleware('role:1,2,8')->group(function () { //geo letter
        Route::get('/pengajuan-surat', [PengajuanPersuratanController::class, 'index'])->name('pengajuansurat');
        Route::get('/pengajuan-surat/getdata', [PengajuanPersuratanController::class, 'getData'])->name('pengajuansurat.getdata');
        Route::get('/pengajuan-surat/getjenissurat', [PengajuanPersuratanController::class, 'getJenisSurat'])->name('pengajuansurat.getjenissurat');
        Route::get('/pengajuan-surat/tambah', [PengajuanPersuratanController::class, 'tambahPengajuan'])->name('pengajuansurat.tambah');
        Route::post('/pengajuan-surat/dotambah', [PengajuanPersuratanController::class, 'doTambahPengajuan'])->name('pengajuansurat.dotambah');
        Route::get('/pengajuan-surat/detail/{id_pengajuan}', [PengajuanPersuratanController::class, 'detailPengajuan'])->name('pengajuansurat.detail');
        Route::post('/pengajuan-surat/doupdate', [PengajuanPersuratanController::class, 'doUpdatePengajuan'])->name('pengajuansurat.doupdate');
        Route::post('/pengajuan-surat/hapus', [PengajuanPersuratanController::class, 'hapusPengajuan'])->name('pengajuansurat.hapus');
        Route::post('/pengajuan-surat/ajukan', [PengajuanPersuratanController::class, 'ajukanPengajuan'])->name('pengajuansurat.ajukan');
        Route::post('/pengajuan-surat/setujui', [PengajuanPersuratanController::class, 'setujuiPengajuan'])->name('pengajuansurat.setujui');
        Route::post('/pengajuan-surat/revisi', [PengajuanPersuratanController::class, 'revisiPengajuan'])->name('pengajuansurat.revisi');
        Route::post('/pengajuan-surat/sudahrevisi', [PengajuanPersuratanController::class, 'sudahRevisiPengajuan'])->name('pengajuansurat.sudahrevisi');
    });

    Route::middleware('role:1,3,6,7,8')->group(function () { //geo room
        Route::get('/pengajuan-ruangan', [PengajuanGeoRoomController::class, 'index'])->name('pengajuanruangan');
    });

    Route::middleware('role:1,4,5,7,8')->group(function () { //geo facility
        Route::get('/pengajuan-peralatan', [PengajuanGeoFacilityController::class, 'index'])->name('pengajuanperalatan');
    });

    Route::middleware('role:1')->group(function () { //super admin
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
    });

    //ruangan
    Route::get('/ruangan', [RuanganController::class, 'index'])->name('ruangan');

    //peralatan
    Route::get('/peralatan', [PeralatanController::class, 'index'])->name('peralatan');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/get-private-file/{id_file}', [FileController::class, 'getPrivateFile'])->middleware('nocache')->name('file.getprivatefile');
});

require __DIR__.'/auth.php';
