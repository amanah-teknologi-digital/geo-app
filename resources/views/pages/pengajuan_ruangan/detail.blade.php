@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts/contentNavbarLayout')

@section('title', $title.' • '.config('variables.templateName'))

@section('page-style')
    @vite([
        'resources/assets/vendor/scss/pages/page-auth.scss',
        'resources/assets/css/custom.scss'
    ])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="#">Pengajuan</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('pengajuanruangan') }}">{{ (!empty(config('variables.namaLayananSewaRuangan')) ? config('variables.namaLayananSewaRuangan') : '') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </nav>
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        {{ $error }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endforeach
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-6">
                        <div class="card-header d-flex justify-content-between align-items-center pb-4 border-bottom">
                            <h5 class="card-title mb-0"><i class="bx bx-user"></i>&nbsp;Detail Pengajuan</h5>
                            <a href="{{ route('pengajuanruangan') }}" class="btn btn-sm btn-secondary btn-sm">
                                <i class="bx bx-arrow-back"></i>&nbsp;Kembali
                            </a>
                        </div>
                        <div class="card-body pt-0 p-3 pb-0">
                            <div class="row">
                                <div class="bs-stepper" id="wizard" style="font-size: 90% !important;">
                                    <div class="bs-stepper-header">
                                        <div class="step" data-target="#data-pemohon">
                                            <button type="button" class="step-trigger"><span class="bs-stepper-circle">1</span><span class="bs-stepper-label"><span class="bs-stepper-title">Data Pemohon</span><span class="bs-stepper-subtitle">Detail Data Pemohon</span></span></button>
                                        </div>
                                        <div class="line">
                                            <i class="icon-base bx bx-chevron-right icon-md"></i>
                                        </div>
                                        <div class="step" data-target="#data-ruangan">
                                            <button type="button" class="step-trigger" ><span class="bs-stepper-circle">2</span><span class="bs-stepper-label"><span class="bs-stepper-title">Jadwal & Ruangan</span><span class="bs-stepper-subtitle">Input Jadwal Booking</span></span></button>
                                        </div>
                                        <div class="line">
                                            <i class="icon-base bx bx-chevron-right icon-md"></i>
                                        </div>
                                        <div class="step" data-target="#data-pengajuan">
                                            <button type="button" class="step-trigger"><span class="bs-stepper-circle">3</span><span class="bs-stepper-label"><span class="bs-stepper-title">Data Pengajuan</span><span class="bs-stepper-subtitle">Input Detail Pengajuan</span></span></button>
                                        </div>
                                    </div>
                                    <div class="bs-stepper-content">
                                        <form method="POST" id="wizard-validation" action="{{ route('pengajuanruangan.doupdate') }}" onsubmit="return false">
                                            @csrf
                                            <div id="data-pemohon" class="content">
                                                <div class="row g-4">
                                                    <div class="col-sm-6">
                                                        <label class="form-label" >Nama Pengaju <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" placeholder="nama pengaju" readonly value="{{ $dataPengajuan->nama_pengaju }}">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label class="form-label" >Nomor Kartu ID (NRP/KTP) <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" placeholder="nomor kartu id (NRP/KTP)" readonly value="{{ $dataPengajuan->kartu_id }}">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label  class="form-label">No. Hp <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" value="{{ $dataPengajuan->no_hp }}" readonly>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" value="{{ $dataPengajuan->email }}" readonly>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label class="form-label">Email ITS<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" value="{{ $dataPengajuan->email_its }}" readonly>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label class="form-label">Status Peminjam<span class="text-danger">*</span></label>
                                                        <select class="form-control" name="status_peminjam" id="status_peminjam" style="font-size: 100%">
                                                            <option value="" selected disabled>-- Pilih Status Peminjam --</option>
                                                            @foreach($dataStatusPeminjam as $status)
                                                                <option value="{{ $status->id_statuspengaju }}" <?= $dataPengajuan->id_statuspengaju == $status->id_statuspengaju? 'selected':'' ?>>{{ $status->nama }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div  class="col-sm-6">
                                                        <label class="form-label">File Kartu ID (NRP/KTP) <span class="text-danger">*</span></label>
                                                        @php
                                                            $file = $dataPengajuan->pihakpengaju->file_kartuid;
                                                            $filePath = $dataPengajuan->pihakpengaju->files->location;
                                                            $imageUrl2 = Storage::disk('local')->exists($filePath)
                                                                ? route('file.getprivatefile', $file)
                                                                : asset('assets/img/no_image.jpg');
                                                        @endphp
                                                        <div class="d-flex align-items-center gap-2">
                                                            <img src="{{ $imageUrl2 }}" class="d-block h-px-100 rounded">
                                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modals-transparent">
                                                                Lihat file
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 d-flex justify-content-between">
                                                        <div></div>
                                                        <button class="btn btn-primary btn-next" id="btn-next-1">
                                                            <span class="align-middle d-sm-inline-block">Selanjutnya</span>
                                                            <i class="icon-base bx bx-chevron-right icon-sm me-sm-n2"></i>
                                                        </button>
                                                    </div>
                                                    <div class="col-12">
                                                        <ul class="fa-ul ml-auto float-end mt-5">
                                                            <li>
                                                                <small><em>Mohon periksa <b>data diri anda</b> sebelum melanjutkan ke tahap berikutnya.</em></small>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="data-ruangan" class="content">
                                                <div class="row g-6 app-calendar-wrapper">
                                                    <div class="row g-0 w-100">
                                                        <div class="col app-calendar-sidebar border-end" id="app-calendar-sidebar">
                                                            <div class="px-6 pb-2 my-sm-0 p-4">
                                                                <div class="mb-4">
                                                                    <label class="form-label" for="ruangan">Pilih Ruangan <span class="text-danger">*</span></label>
                                                                    <select name="ruangan[]" id="ruangan" class="form-control" multiple style="font-size: 100%">
                                                                        @foreach($dataRuangan as $ruangan)
                                                                            <option value="{{ $ruangan->id_ruangan }}">{{ $ruangan->kode_ruangan.' - '.$ruangan->nama }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="error-container" id="error-ruangan"></div>
                                                                </div>
                                                                <div class="mb-4">
                                                                    <label class="form-label" for="tanggal_booking">Pilih Tanggal <span class="text-danger">*</span></label>
                                                                    <input type="text" name="tanggal_booking" id="tanggal_booking" class="form-control">
                                                                </div>
                                                                <div class="mb-4">
                                                                    <label class="form-label" for="jam_jadwal">Pilih Waktu <span class="text-danger">*</span></label>
                                                                    <div class="d-inline-flex gap-2">
                                                                        <input type="text" id="jam_mulai" class="form-control jam_jadwal" name="jam_mulai" placeholder="pilih jam mulai" autocomplete="off">
                                                                        <input type="text" id="jam_selesai" class="form-control jam_jadwal" name="jam_selesai" placeholder="pilih jam selesai" autocomplete="off">
                                                                    </div>
                                                                    <div class="error-container" id="error-jammulai"></div>
                                                                    <div class="error-container" id="error-jamselesai"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col app-calendar-content">
                                                            <div class="card shadow-none border-0">
                                                                <div class="card-body pb-0 border-bottom">
                                                                    <div id="calendar" style="width: 100%;"></div>
                                                                </div>
                                                            </div>
                                                            <div class="app-overlay"></div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 d-flex justify-content-between">
                                                        <button class="btn btn-secondary btn-prev" id="btn-prev-1">
                                                            <i class="icon-base bx bx-chevron-left icon-sm ms-sm-n2 me-sm-2"></i>
                                                            <span class="align-middle d-sm-inline-block">Sebelumnya</span>
                                                        </button>
                                                        <button class="btn btn-primary btn-next" id="btn-next-2">
                                                            <span class="spinner-border me-2 spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                            <span class="btn-text">
                                            <span class="align-middle d-sm-inline-block">Selanjutnya</span>
                                            <i class="icon-base bx bx-chevron-right icon-sm me-sm-n2 me-sm-2"></i>
                                        </span>
                                                        </button>
                                                    </div>
                                                    <div class="col-12">
                                                        <ul class="fa-ul ml-auto float-end mt-5">
                                                            <li>
                                                                <small><em>Jadwal yang tersedia adalah <b>H + 1</b> dari waktu pengajuan.</em></small>
                                                            </li>
                                                            <li>
                                                                <small><em>Jika hari bersifat <b>range</b> maka dibooking sesuai <b>waktu mulai dan selesai per hari & ruangan yang dipilih</b>.</em></small>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="data-pengajuan" class="content">
                                                <div class="content-header mb-4">
                                                    <h6 class="mb-0">Data Pengajuan</h6>
                                                    <small>Input Detail Pengajuan.</small>
                                                </div>
                                                <div class="row g-6">
                                                    <div class="col-sm-12">
                                                        <label class="form-label" for="nama_kegiatan">Nama Kegiatan <span class="text-danger">*</span></label>
                                                        <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control" placeholder="nama kegiatan" autocomplete="off" value="{{ $dataPengajuan->nama_kegiatan }}">
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <label class="form-label" for="deskripsi_kegiatan">Deskripsi Kegiatan <span class="text-danger">*</span></label>
                                                        <textarea rows="5" cols="5" name="deskripsi_kegiatan" id="deskripsi_kegiatan" class="form-control" placeholder="deskripsi kegiatan">{{ $dataPengajuan->deskripsi }}</textarea>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <label class="form-label" for="deskripsi_kegiatan">Data Peminjaman Sarana/Prasarana <span class="text-danger">*</span></label>
                                                        <div class="table-responsive" id="tabelPeminjaman">

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 mb-10">
                                                        <div class="form-check form-check-primary">
                                                            <input type="checkbox" class="form-check-input" id="terms" name="terms" checked>
                                                            <label class="form-check-label" for="terms">Dengan ini, saya mengonfirmasi bahwa semua informasi yang diberikan adalah akurat dan lengkap.</label>
                                                        </div>
                                                        <div class="error-container" id="error-terms"></div>
                                                    </div>

                                                    <div class="col-12 d-flex justify-content-between">
                                                        <button class="btn btn-secondary btn-prev" id="btn-prev-2">
                                                            <i class="icon-base bx bx-chevron-left icon-sm ms-sm-n2 me-sm-2"></i>
                                                            <span class="align-middle d-sm-inline-block">Sebelumnya</span>
                                                        </button>
                                                        <button class="btn btn-warning text-black" id="btn-save">
                                                            <i class="icon-base bx bx-save icon-sm"></i>&nbsp;
                                                            <span class="align-middle d-sm-inline-block">Update Pengajuan</span>
                                                        </button>
                                                    </div>
                                                    <div class="col-12">
                                                        <ul class="fa-ul ml-auto float-end mt-5">
                                                            <li>
                                                                <small><em>Data peralatan bisa mengajukan <b>lebih dari 1</b> dengan menekan tombol <b>tambah peralatan</b>.</em></small>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
        <div id="liveToast" class="bs-toast toast fade" role="alert">
            <div class="toast-header">
                <i class="icon-base bx bx-bell me-2"></i>
                <div class="me-auto fw-medium">Notifikasi</div>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toast-message"></div>
        </div>
    </div>
    <div class="modal modal-transparent fade" id="modals-transparent" tabindex="-1" style="border: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background: rgba(0, 0, 0, 0);border: none;color: white;">
                <div class="modal-body">
                    <img id="kartu_idmodal" src="{{ $imageUrl2 }}" class="img-fluid w-100 h-100 object-fit-cover" alt="kartu ID">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" >Detail Jadwal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="w-100 p-5">
                        <tr>
                            <td style="width: 35%">Nama Ruangan</td>
                            <td style="width: 1%">:</td>
                            <td style="width: 64%">&nbsp;<span class="fw-bold" id="eventModalNamaRuangan"></span></td>
                        </tr>
                        <tr>
                            <td style="width: 35%">Keterangan</td>
                            <td style="width: 1%">:</td>
                            <td style="width: 64%">&nbsp;<span class="fst-italic" id="eventModalTitle"></span></td>
                        </tr>
                        <tr>
                            <td style="width: 35%">Waktu Mulai</td>
                            <td style="width: 1%">:</td>
                            <td style="width: 64%">&nbsp;<span class="text-muted fw-bold fst-italic" id="eventModalStart"></span></td>
                        </tr>
                        <tr>
                            <td style="width: 35%">Waktu Selesai</td>
                            <td style="width: 1%">:</td>
                            <td style="width: 64%">&nbsp;<span class="text-muted fw-bold fst-italic" id="eventModalEnd"></span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-ajukan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <form action="{{ route('pengajuansurat.ajukan') }}" method="POST">
                @csrf
                <input type="hidden" name="id_pengajuan" value="{{ $idPengajuan }}" >
                <input type="hidden" name="id_akses" id="id_akses_ajukan" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel2">Ajukan Pengajuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah yakin mengajukan pengajuan ini?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Iya</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="modal-hapusfile" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <form action="{{ route('pengajuansurat.hapusfile') }}" method="POST">
                @csrf
                <input type="hidden" name="id_pengajuan" value="{{ $idPengajuan }}" >
                <input type="hidden" name="id_file" id="id_filehapus" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel2">Hapus File</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah yakin menghapus file ini?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Iya</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="modal-setujui" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <form id="frmSetujuiPengajuan" action="{{ route('pengajuansurat.setujui') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_pengajuan" value="{{ $idPengajuan }}" >
                <input type="hidden" name="id_akses" id="id_akses_setujui" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel2">Setujui Pengajuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah yakin menyetujui pengajuan ini?</p>
                        <div>
                            <label for="filesurat" class="form-label">File Hasil Surat <i class="text-muted fw-bold">(Opsional & bisa lebih dari 1, PDF Max 5 MB)</i></label>
                            <input type="file" class="form-control" name="filesurat[]" id="filesurat" accept="application/pdf" multiple autofocus>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Iya</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="modal-revisi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <form action="{{ route('pengajuansurat.revisi') }}" method="POST">
                @csrf
                <input type="hidden" name="id_pengajuan" value="{{ $idPengajuan }}" >
                <input type="hidden" name="id_akses" id="id_akses_revisi" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel2">Revisi Pengajuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label for="keteranganrev" class="form-label">Keterangan <span class="text-danger">*</span></label>
                            <textarea name="keteranganrev" id="keteranganrev" class="form-control" cols="10" rows="5" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Revisi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="modal-sudahrevisi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <form action="{{ route('pengajuansurat.sudahrevisi') }}" method="POST">
                @csrf
                <input type="hidden" name="id_pengajuan" value="{{ $idPengajuan }}" >
                <input type="hidden" name="id_akses" id="id_akses_sudahrevisi" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel2">Sudah Revisi Pengajuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label for="keterangansudahrev" class="form-label">Keterangan <span class="text-danger">*</span></label>
                            <textarea name="keterangansudahrev" id="keterangansudahrev" class="form-control" cols="10" rows="5" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-info">Ajukan Revisi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="modal-tolak" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <form action="{{ route('pengajuansurat.tolak') }}" method="POST">
                @csrf
                <input type="hidden" name="id_pengajuan" value="{{ $idPengajuan }}" >
                <input type="hidden" name="id_akses" id="id_akses_tolak" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel2">Tolak Pengajuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label for="keterangantolak" class="form-label">Keterangan <span class="text-danger">*</span></label>
                            <textarea name="keterangantolak" id="keterangantolak" class="form-control" cols="10" rows="5" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @php
        $dataPeralatan = $dataPengajuan->pengajuanperalatandetail->map(function ($item) {
            return [
                'nama_sarana' => $item->nama_sarana,
                'jumlah' => $item->jumlah,
            ];
        });
    @endphp
@endsection
@section('page-script')
    <script>
        const isEdit = {{ $isEdit ? 'true' : 'false' }};
        const tglMulai = '{{ $dataPengajuan->tgl_mulai }}';
        const tglSelesai = '{{ $dataPengajuan->tgl_selesai }}';
        const jamMulai = '{{ $dataPengajuan->jam_mulai }}';
        const jamSelesai = '{{ $dataPengajuan->jam_selesai }}';
        const idRuangan = @json($dataPengajuan->pengajuanruangandetail->pluck('id_ruangan')->toArray());
        const dataPeralatan = @json($dataPeralatan);
        const urlGetData = '{{ route('pengajuanruangan.getdatajadwal') }}';
        const urlCheckJadwalRuangan = '{{ route('pengajuanruangan.cekdatajadwal') }}';
    </script>
    @vite('resources/views/script_view/pengajuan_ruangan/detail_pengajuan.js')
@endsection
