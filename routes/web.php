<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\JenisSuratController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ManajemenUserController;
use App\Http\Controllers\PengajuanGeoFacilityController;
use App\Http\Controllers\PengajuanPersuratanController;
use App\Http\Controllers\PengajuanRuanganController;
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
    Route::post('/ganti-hakakses', [DashboardController::class, 'gantiHakAkses'])->name('gantihakakses');
    Route::get('/dashboard/getdatanotifikasi', [DashboardController::class, 'getDataNotifikasi'])->name('dashboard.getdatanotifikasi');
    Route::get('/dashboard-surat', [DashboardController::class, 'surat'])->middleware('role:1,2,5,6,7')->name('dashboard.surat');
    Route::get('/dashboard-surat/getdata', [DashboardController::class, 'getDataSurat'])->middleware('role:1,2,5,6,7')->name('dashboard.suratgetdata');
    Route::get('/dashboard-ruangan', [DashboardController::class, 'ruangan'])->middleware('role:1,3,5,6,7')->name('dashboard.ruangan');
    Route::get('/dashboard-peralatan', [DashboardController::class, 'peralatan'])->middleware('role:1,4,5,6,7')->name('dashboard.peralatan');
    Route::get('/dashboard-pengguna', [DashboardController::class, 'pengguna'])->middleware('role:8')->name('dashboard.pengguna');
    Route::get('/dashboard-pengguna/getdata', [DashboardController::class, 'getDataSuratPengguna'])->middleware('role:8')->name('dashboard.suratgetdatapengguna');

    Route::middleware('role:1,2,8')->group(function () { //geo letter
        Route::get('/pengajuan-surat', [PengajuanPersuratanController::class, 'index'])->name('pengajuansurat');
        Route::get('/pengajuan-surat/getdata', [PengajuanPersuratanController::class, 'getData'])->name('pengajuansurat.getdata');
        Route::get('/pengajuan-surat/getjenissurat', [PengajuanPersuratanController::class, 'getJenisSurat'])->name('pengajuansurat.getjenissurat');
        Route::middleware('role:1,8')->group(function () { //yang bisa mengajukan
            Route::get('/pengajuan-surat/tambah', [PengajuanPersuratanController::class, 'tambahPengajuan'])->name('pengajuansurat.tambah');
            Route::post('/pengajuan-surat/dotambah', [PengajuanPersuratanController::class, 'doTambahPengajuan'])->name('pengajuansurat.dotambah');
        });
        Route::get('/pengajuan-surat/detail/{id_pengajuan}', [PengajuanPersuratanController::class, 'detailPengajuan'])->name('pengajuansurat.detail');
        Route::post('/pengajuan-surat/doupdate', [PengajuanPersuratanController::class, 'doUpdatePengajuan'])->name('pengajuansurat.doupdate');
        Route::post('/pengajuan-surat/hapus', [PengajuanPersuratanController::class, 'hapusPengajuan'])->name('pengajuansurat.hapus');
        Route::post('/pengajuan-surat/ajukan', [PengajuanPersuratanController::class, 'ajukanPengajuan'])->name('pengajuansurat.ajukan');
        Route::post('/pengajuan-surat/setujui', [PengajuanPersuratanController::class, 'setujuiPengajuan'])->name('pengajuansurat.setujui');
        Route::post('/pengajuan-surat/revisi', [PengajuanPersuratanController::class, 'revisiPengajuan'])->name('pengajuansurat.revisi');
        Route::post('/pengajuan-surat/sudahrevisi', [PengajuanPersuratanController::class, 'sudahRevisiPengajuan'])->name('pengajuansurat.sudahrevisi');
        Route::post('/pengajuan-surat/tolak', [PengajuanPersuratanController::class, 'tolakPengajuan'])->name('pengajuansurat.tolak');
        Route::post('/pengajuan-surat/hapusfile', [PengajuanPersuratanController::class, 'hapusFile'])->name('pengajuansurat.hapusfile');
        Route::post('/pengajuan-surat/uploadFile', [PengajuanPersuratanController::class, 'uploadFile'])->name('pengajuansurat.uploadfile');

        Route::middleware('role:1,2')->group(function () { //bisa manajemen jenis surat
            Route::get('/jenis-surat', [JenisSuratController::class, 'index'])->name('jenissurat');
            Route::get('/jenis-surat/getdata', [JenisSuratController::class, 'getData'])->name('jenissurat.getdata');
            Route::get('/jenis-surat/tambah', [JenisSuratController::class, 'tambahJenisSurat'])->name('jenissurat.tambah');
            Route::post('/jenis-surat/dotambah', [JenisSuratController::class, 'doTambahJenisSurat'])->name('jenissurat.dotambah');
            Route::get('/jenis-surat/edit/{id_jenissurat}', [JenisSuratController::class, 'editJenisSurat'])->name('jenissurat.edit');
            Route::post('/jenis-surat/doedit', [JenisSuratController::class, 'doEditJenisSurat'])->name('jenissurat.doedit');
            Route::post('/jenis-surat/hapus', [JenisSuratController::class, 'hapusJenisSurat'])->name('jenissurat.hapus');
            Route::post('/jenis-surat/aktifkan', [JenisSuratController::class, 'aktifkanJenisSurat'])->name('jenissurat.aktifkan');
            Route::post('/jenis-surat/nonaktifkan', [JenisSuratController::class, 'nonAktifkanJenisSurat'])->name('jenissurat.nonaktifkan');
            Route::post('/jenis-surat/dotambahpenyetuju', [JenisSuratController::class, 'doTambahPenyetuju'])->name('jenissurat.dotambahpenyetuju');
            Route::post('/jenis-surat/doupdatepenyetuju', [JenisSuratController::class, 'doUpdatePenyetuju'])->name('jenissurat.doupdatepenyetuju');
            Route::post('/jenis-surat/dohapuspenyetuju', [JenisSuratController::class, 'doHapusPenyetuju'])->name('jenissurat.dohapuspenyetuju');
            Route::get('/jenis-surat/getuserpenyetuju', [JenisSuratController::class, 'getUserPenyetuju'])->name('jenissurat.getuserpenyetuju');
            Route::get('/jenis-surat/getuserpenyetujuupdate', [JenisSuratController::class, 'getUserPenyetujuUpdate'])->name('jenissurat.getuserpenyetujuupdate');
        });
    });

    //ruangan
    Route::get('/ruangan', [RuanganController::class, 'index'])->name('ruangan');
    Route::get('/ruangan/detail/{id_ruangan}', [RuanganController::class, 'detailRuangan'])->name('ruangan.detail');
    Route::get('/ruangan/jadwal/{id_ruangan}', [RuanganController::class, 'jadwalRuangan'])->name('ruangan.jadwal');
    Route::get('/ruangan/getdatajadwal', [RuanganController::class, 'getDataJadwal'])->name('ruangan.getdatajadwal');
    Route::post('/pengajuan-ruangan/cekdatajadwal', [PengajuanRuanganController::class, 'cekDataJadwal'])->name('pengajuanruangan.cekdatajadwal');
    Route::post('/pengajuan-ruangan/getdatajadwal', [PengajuanRuanganController::class, 'getDataJadwal'])->name('pengajuanruangan.getdatajadwal');
    Route::middleware('role:1,3,6,7,8')->group(function () {
        Route::get('/pengajuan-ruangan', [PengajuanRuanganController::class, 'index'])->name('pengajuanruangan');
        Route::get('/pengajuan-ruangan/getdata', [PengajuanRuanganController::class, 'getData'])->name('pengajuanruangan.getdata');
        Route::get('/pengajuan-ruangan/detail/{id_pengajuan}', [PengajuanRuanganController::class, 'detailPengajuan'])->name('pengajuanruangan.detail');
        Route::middleware('role:1,8')->group(function () { //yang bisa mengajukan
            Route::get('/pengajuan-ruangan/tambah', [PengajuanRuanganController::class, 'tambahPengajuan'])->name('pengajuanruangan.tambah');
            Route::post('/pengajuan-ruangan/dotambah', [PengajuanRuanganController::class, 'doTambahPengajuan'])->name('pengajuanruangan.dotambah');
            Route::post('/pengajuan-ruangan/doupdate', [PengajuanRuanganController::class, 'doUpdatePengajuan'])->name('pengajuanruangan.doupdate');
            Route::post('/pengajuan-ruangan/dohapus', [PengajuanRuanganController::class, 'doHapusPengajuan'])->name('pengajuanruangan.hapus');
        });
        Route::middleware('role:1,3')->group(function () { //bisa manajemen ruangan
            Route::get('/ruangan/tambah', [RuanganController::class, 'tambahRuangan'])->name('ruangan.tambah');
            Route::post('/ruangan/dotambah', [RuanganController::class, 'doTambahRuangan'])->name('ruangan.dotambah');
            Route::post('/ruangan/doupdate', [RuanganController::class, 'doUpdateRuangan'])->name('ruangan.doupdate');
            Route::post('/ruangan/dotambahjadwal', [RuanganController::class, 'doTambahJadwal'])->name('ruangan.dotambahjadwal');
            Route::post('/ruangan/doupdatejadwal', [RuanganController::class, 'doUpdateJadwal'])->name('ruangan.doupdatejadwal');
            Route::post('/ruangan/dohapusjadwal', [RuanganController::class, 'doHapusJadwal'])->name('ruangan.dohapusjadwal');
        });
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

        //manajemen user
        Route::get('/manajemen-user', [ManajemenUserController::class, 'index'])->name('manajemen-user');
    });

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
