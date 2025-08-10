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
                        <a href="{{ route('pengajuansurat') }}">{{ (!empty(config('variables.namaLayananPersuratan')) ? config('variables.namaLayananPersuratan') : '') }}</a>
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
            <div class="card mb-6">
                <div class="card-header d-flex justify-content-between align-items-center pb-4 border-bottom">
                    <h5 class="card-title mb-0"><i class="bx bx-plus mb-1"></i>&nbsp;Tambah Pengajuan</h5>
                    <a href="{{ route('pengajuansurat') }}" class="btn btn-sm btn-secondary btn-sm">
                        <i class="bx bx-arrow-back"></i>&nbsp;Kembali
                    </a>
                </div>
                <div class="card-body pt-4">
                    <form id="formPengajuan" method="POST" action="{{ route('pengajuansurat.dotambah') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-6">
                            <div class="col-md-12">
                                <p class="card-title mb-0 fw-bold d-flex align-items-center">
                                    <i class="bx bx-user me-2" style="font-size: 1.3rem;"></i>
                                    Data Pemohon
                                </p>
                                <br>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="fw-semibold small text-secondary mb-3">Nama Pengaju </div>
                                        <div class="fs-6 text-dark">{{ Auth()->user()->name }}</div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="fw-semibold small text-secondary mb-3">Nomor Kartu ID (NRP/KTP) </div>
                                        <div class="fs-6 text-dark">{{ Auth()->user()->kartu_id }}</div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="fw-semibold small text-secondary mb-3">No. Hp </div>
                                        <div class="fs-6 text-dark">{{ Auth()->user()->no_hp }}</div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="fw-semibold small text-secondary mb-3">Email </div>
                                        <div class="fs-6 text-dark">{{ Auth()->user()->email }}</div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="fw-semibold small text-secondary mb-3">Email ITS </div>
                                        <div class="fs-6 text-dark">{{ Auth()->user()->email_its }}</div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="fw-semibold small text-secondary mb-1">File Kartu ID (NRP/KTP) </div>
                                        @php
                                            $file = auth()->user()->file_kartuid;
                                            $filePath = auth()->user()->files->location;
                                            $imageUrl = Storage::disk('private')->exists($filePath)
                                                ? route('file.getprivatefile', $file)
                                                : asset('assets/img/no_image.jpg');
                                        @endphp
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $imageUrl }}" class="rounded border shadow-sm" style="height: 80px; object-fit: cover;">
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                    data-bs-target="#modals-transparent">
                                                Lihat file
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row g-6">
                            <p class="card-title mb-0 fw-bold d-flex align-items-center">
                                <i class="bx bx-envelope pb-0" style="font-size: 1.3rem;"></i>&nbsp;Data Persuratan
                            </p>
                            <br>
                            <div>
                                <label for="jenis_surat" class="form-label">Jenis Surat <span class="text-danger">*</span></label>
                                <select name="jenis_surat" id="jenis_surat" class="form-control" required autofocus>
                                    <option value="" selected disabled>-- Pilih Jenis Surat --</option>
                                    @foreach($dataJenisSurat as $row)
                                        <option value="{{ $row->id_jenissurat }}">{{ $row->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="email" class="form-label">Persetujuan</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div id="list-persetujuan" style="font-weight: bold;">-</div>
                                </div>
                            </div>
                            <div>
                                <label for="isi_surat" class="form-label">Form Isi Surat <span class="text-danger">*</span></label>
                                <div id="editor-loading" class="text-center">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <textarea id="editor_surat" name="editor_surat" style="height: 500px;"></textarea>
                                <div class="error-container" id="error-quil"></div>
                            </div>
                            <div>
                                <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
                                <textarea name="keterangan" id="keterangan" class="form-control" cols="10" rows="5" required></textarea>
                            </div>
                            <div id="div_datapendukung" style="display: none">
                                <label for="data_pendukung" class="form-label">Data Pendukung <span id="nama_datapendukung" class="text-muted fst-italic"></span> <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="data_pendukung" id="data_pendukung" accept="application/pdf">
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="btn btn-primary me-3"><i class="bx bx-save"></i>&nbsp;Tambah Pengajuan</button>
                        </div>
                    </form>
                    <ul class="fa-ul ml-auto float-end mt-5">
                        <li>
                            <small><em>Ganti text yang <b>bewarna kuning</b> sesuai data yang akan diajukan!.</em></small>
                        </li>
                    </ul>
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
        const routeGetJenisSurat = "{{ route('pengajuansurat.getjenissurat') }}";
        const namaLayananSurat = "{{ $namaLayananSurat }}";
    </script>
    @vite('resources/views/script_view/pengajuan_surat/tambah_pengajuan.js')
@endsection
