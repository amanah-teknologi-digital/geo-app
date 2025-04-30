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
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center pb-4 border-bottom">
                    <h5 class="card-title mb-0"><i class="bx bx-plus mb-1"></i>&nbsp;Tambah Pengajuan</h5>
                    <a href="{{ route('pengajuanruangan') }}" class="btn btn-sm btn-secondary btn-sm">
                        <i class="bx bx-arrow-back"></i>&nbsp;Kembali
                    </a>
                </div>
            </div>
            <div class="bs-stepper mt-2">
                <div class="bs-stepper-header">
                    <div class="step" data-target="#data-pemohon">
                        <button type="button" class="step-trigger"><span class="bs-stepper-circle">1</span><span class="bs-stepper-label"><span class="bs-stepper-title">Data Pemohon</span><span class="bs-stepper-subtitle">Detail Data Pemohon</span></span></button>
                    </div>
                    <div class="line">
                        <i class="icon-base bx bx-chevron-right icon-md"></i>
                    </div>
                    <div class="step" data-target="#data-ruangan">
                        <button type="button" class="step-trigger" ><span class="bs-stepper-circle">2</span><span class="bs-stepper-label"><span class="bs-stepper-title">Pilih Ruangan</span><span class="bs-stepper-subtitle">Input Jadwal Booking</span></span></button>
                    </div>
                    <div class="line">
                        <i class="icon-base bx bx-chevron-right icon-md"></i>
                    </div>
                    <div class="step" data-target="#data-pengajuan">
                        <button type="button" class="step-trigger"><span class="bs-stepper-circle">3</span><span class="bs-stepper-label"><span class="bs-stepper-title">Data Pengajuan</span><span class="bs-stepper-subtitle">Input Detail Pengajuan</span></span></button>
                    </div>
                </div>
                <div class="bs-stepper-content">
                    <form id="wizard-validation" action="{{ route('pengajuanruangan.dotambah') }}" onsubmit="return false">
                        <div id="data-pemohon" class="content">
                            <div class="content-header mb-4">
                                <h6 class="mb-0">Data Pemohon</h6>
                                <small>Input Detail Data Pemohon.</small>
                            </div>
                            <div class="row g-6">
                                <div class="col-sm-6">
                                    <label class="form-label" >Nama Pengaju <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="nama pengaju" readonly value="{{ auth()->user()->name }}">
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" >Nomor Kartu ID (NRP/KTP) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="nomor kartu id (NRP/KTP)" readonly value="{{ auth()->user()->kartu_id }}">
                                </div>
                                <div class="col-sm-6">
                                    <label  class="form-label">No. Hp <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ Auth()->user()->no_hp }}" readonly>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ Auth()->user()->email }}" readonly>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Email ITS<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ Auth()->user()->email_its }}" readonly>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Status Peminjam<span class="text-danger">*</span></label>
                                    <select class="form-control" name="status_peminjam" id="status_peminjam" required>
                                        <option value="" selected disabled>-- Pilih Status Peminjam --</option>
                                        @foreach($dataStatusPeminjam as $status)
                                            <option value="{{ $status->id_statuspengaju }}">{{ $status->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div  class="col-sm-6">
                                    <label class="form-label">File Kartu ID (NRP/KTP) <span class="text-danger">*</span></label>
                                    @php
                                        $file = auth()->user()->file_kartuid;
                                        $filePath = auth()->user()->files->location;
                                        $imageUrl = Storage::disk('local')->exists($filePath)
                                            ? route('file.getprivatefile', $file)
                                            : asset('assets/img/no_image.jpg');
                                    @endphp
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $imageUrl }}" class="d-block h-px-100 rounded">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modals-transparent">
                                            Lihat file
                                        </button>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-between">
                                    <div></div>
                                    <button class="btn btn-primary btn-next" id="btn-next-1">
                                        <span class="align-middle d-sm-inline-block d-none me-sm-2">Selanjutnya</span>
                                        <i class="icon-base bx bx-chevron-right icon-sm me-sm-n2"></i>
                                    </button>
                                </div>
                                <div class="col-12">
                                    <ul class="fa-ul ml-auto float-end mt-5">
                                        <li>
                                            <small><em>Jadwal yang tersedia adalah <b>H + 1</b> dari waktu pengajuan.</em></small>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div id="data-ruangan" class="content">
                            <div class="content-header mb-4">
                                <h6 class="mb-0">Data Pemohon</h6>
                                <small>Input Detail Data Pemohon.</small>
                            </div>
                            <div class="row g-6">

                                <div class="col-12 d-flex justify-content-between">
                                    <button class="btn btn-secondary btn-prev" id="btn-prev-1">
                                        <i class="icon-base bx bx-chevron-left icon-sm ms-sm-n2 me-sm-2"></i>
                                        <span class="align-middle d-sm-inline-block">Sebelumnya</span>
                                    </button>
                                    <button class="btn btn-primary btn-next" id="btn-next-2">
                                        <span class="align-middle d-sm-inline-block d-none me-sm-2">Selanjutnya</span>
                                        <i class="icon-base bx bx-chevron-right icon-sm me-sm-n2"></i>
                                    </button>
                                </div>
                                <div class="col-12">
                                    <ul class="fa-ul ml-auto float-end mt-5">
                                        <li>
                                            <small><em>Jadwal yang tersedia adalah <b>H + 1</b> dari waktu pengajuan.</em></small>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div id="data-pengajuan" class="content">
                            <div class="content-header mb-4">
                                <h6 class="mb-0">Data Pengajuan</h6>
                                <small>Input Detail Data Pemohon.</small>
                            </div>
                            <div class="row g-6">

                                <div class="col-12 d-flex justify-content-between">
                                    <button class="btn btn-secondary btn-prev" id="btn-prev-2">
                                        <i class="icon-base bx bx-chevron-left icon-sm ms-sm-n2 me-sm-2"></i>
                                        <span class="align-middle d-sm-inline-block">Sebelumnya</span>
                                    </button>
                                    <button class="btn btn-success" id="btn-save">
                                        <i class="icon-base bx bx-save icon-sm"></i>&nbsp;
                                        <span class="align-middle d-sm-inline-block">Ajukan Data</span>
                                    </button>
                                </div>
                                <div class="col-12">
                                    <ul class="fa-ul ml-auto float-end mt-5">
                                        <li>
                                            <small><em>Jadwal yang tersedia adalah <b>H + 1</b> dari waktu pengajuan.</em></small>
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
    <div class="modal modal-transparent fade" id="modals-transparent" tabindex="-1" style="border: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background: rgba(0, 0, 0, 0);border: none;color: white;">
                <div class="modal-body">
                    <img id="kartu_idmodal" src="{{ $imageUrl }}" class="img-fluid w-100 h-100 object-fit-cover" alt="kartu ID">
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-script')
    <script>
        let routeGetJenisSurat = "{{ route('pengajuansurat.getjenissurat') }}";
    </script>
    @vite('resources/views/script_view/pengajuan_ruangan/tambah_pengajuan.js')
@endsection
