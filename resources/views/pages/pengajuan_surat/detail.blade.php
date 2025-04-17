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
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-6">
                        <div class="card-header d-flex justify-content-between align-items-center pb-4 border-bottom">
                            <h5 class="card-title mb-0"><i class="bx bx-user"></i>&nbsp;Data Pemohon</h5>
                            <a href="{{ route('pengajuansurat') }}" class="btn btn-sm btn-secondary btn-sm">
                                <i class="bx bx-arrow-back"></i>&nbsp;Kembali
                            </a>
                        </div>
                        <div class="card-body pt-4">
                            <div class="row g-6">
                                <div>
                                    <label for="nama_pengaju" class="form-label">Nama Pengaju <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ Auth()->user()->name }}" readonly>
                                </div>
                                <div>
                                    <label for="kartu_id" class="form-label">Nomor Kartu ID (NRP/KTP) <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ Auth()->user()->kartu_id }}" readonly>
                                </div>
                                <div>
                                    <label for="file_kartu_id" class="form-label">File Kartu ID (NRP/KTP) <span
                                            class="text-danger">*</span></label>
                                    @php
                                        $file = auth()->user()->file_kartuid;
                                        $filePath = auth()->user()->files->location;
                                        $imageUrl = Storage::disk('local')->exists($filePath)
                                            ? route('file.getprivatefile', $file)
                                            : asset('assets/img/no_image.jpg');
                                    @endphp
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $imageUrl }}" class="d-block h-px-100 rounded">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                data-bs-target="#modals-transparent">
                                            Lihat file
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label for="no_hp" class="form-label">No. Hp <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ Auth()->user()->no_hp }}" readonly>
                                </div>
                                <div>
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ Auth()->user()->email }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-6">
                        <div class="card-header d-flex justify-content-between align-items-center pb-4 border-bottom">
                            <h5 class="card-title mb-0"><i class="bx bx-history"></i>&nbsp;Histori Persetujuan</h5>
                        </div>
                        <div class="card-body pt-4">
                            @if($dataPengajuan->persetujuan->isNotEmpty())
                                <ul class="timeline-with-icons">
                                @foreach($dataPengajuan->persetujuan as $pers)
                                    <li class="timeline-item mb-5">
                                        <span class="timeline-icon {{ $pers->statuspersetujuan->class_bg }}"><i class="{{ $pers->statuspersetujuan->class_label }}"></i></span>
                                        <h5 class="mb-0">{{ $pers->statuspersetujuan->nama.' '.$pers->akses->nama }}</h5>
                                        <p class="text-muted fst-italic fs-6">{{ $pers->created_at->format('d/m/Y H:i') }} oleh {{ $pers->nama_penyetuju }}</p>
                                        @if(!empty($pers->keterangan))
                                            <p class="text-muted small"><b>Keterangan:</b> <span class="fst-italic">{{ $pers->keterangan }}</span></p>
                                        @endif
                                    </li>
                                @endforeach
                                </ul>
                            @else
                                <div class="text-center">
                                    <p class="text-muted">Persetujuan Kosong!</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-6">
                <div class="card-header d-flex justify-content-between align-items-center pb-4 border-bottom">
                    <h5 class="card-title mb-0"><i class="bx bx-envelope"></i>&nbsp;Data Persuratan</h5>
                </div>
                <div class="card-body pt-4">
                    <form id="formPengajuan" method="POST" action="{{ route('pengajuansurat.doupdate') }}">
                        @csrf
                        <div class="row g-6">
                            <div>
                                <label for="jenis_surat" class="form-label">Jenis Surat <span
                                        class="text-danger">*</span></label>
                                <select name="jenis_surat" id="jenis_surat" class="form-control" required autofocus {{ $isEdit? '':'disabled' }} >
                                    <option value="" selected disabled>-- Pilih Jenis Surat --</option>
                                    @foreach($dataJenisSurat as $row)
                                        <option value="{{ $row->id_jenissurat }}" {{ ($dataPengajuan->id_jenissurat == $row->id_jenissurat) ? 'selected':'' }}>{{ $row->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="isi_surat" class="form-label">Form Isi Surat <span
                                        class="text-danger">*</span></label>
                                <div id="editor_surat" style="height: 250px;">{!! $dataPengajuan->data_form !!}</div>
                                <input type="hidden" name="editor_quil" id="editor_quil" value="{{ $dataPengajuan->data_form }}">
                                <div class="error-container" id="error-quil"></div>
                            </div>
                            <div>
                                <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
                                <textarea name="keterangan" id="keterangan" class="form-control" cols="10" rows="5" required {{ $isEdit? '':'readonly' }} >{{ $dataPengajuan->keterangan }}</textarea>
                            </div>
                        </div>
                        @if($isEdit)
                            <div class="mt-6">
                                <button type="submit" class="btn btn-warning me-3 text-black"><i class="bx bx-save"></i>&nbsp;Update Pengajuan</button>
                            </div>
                        @endif
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
        let isEdit = {{ $isEdit ? 'true' : 'false' }};
        let routeGetJenisSurat = "{{ route('pengajuansurat.getjenissurat') }}";
    </script>
    @vite('resources/views/script_view/pengajuan_surat/detail_pengajuan.js')
@endsection
