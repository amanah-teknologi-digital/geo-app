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
            @if($dataPengajuan->id_tahapan == '10')
                <div class="row align-items-stretch mb-5">
                    <div class="col-md-12">
                        <div class="card mb-4 shadow-sm h-100 border-0">
                            <div class="card-header d-flex justify-content-between align-items-center pb-3 border-bottom">
                                <h5 class="card-title mb-0 fw-bold d-flex align-items-center">
                                    <i class="bx bx-collection me-2" style="font-size: 1.3rem;"></i>
                                    Surver Kepuasan Layanan Kami
                                </h5>
                                <a href="{{ route('pengajuanruangan') }}" class="btn btn-sm btn-secondary">
                                    <i class="bx bx-arrow-back"></i>&nbsp;Kembali
                                </a>
                            </div>
                            <div class="card-body pt-4">
                                @if(empty($dataPengajuan->surveykepuasan))
                                    <form id="FrmSurveyKepuasan" action="{{ route('pengajuanruangan.surveykepuasan') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id_pengajuan" value="{{ $idPengajuan }}">

                                        <p>Terima kasih telah menggunakan layanan kami. Mohon luangkan waktu sejenak untuk mengisi survei kepuasan berikut agar kami bisa terus meningkatkan kualitas layanan.</p>

                                        <div class="mb-3">
                                            <label class="form-label">Beri Rating Kepuasan Anda:</label>
                                            <div class="rating">
                                                <input type="radio" id="star5" name="rating" value="5" />
                                                <label for="star5" title="5 stars">★</label>

                                                <input type="radio" id="star4" name="rating" value="4" />
                                                <label for="star4" title="4 stars">★</label>

                                                <input type="radio" id="star3" name="rating" value="3" />
                                                <label for="star3" title="3 stars">★</label>

                                                <input type="radio" id="star2" name="rating" value="2" />
                                                <label for="star2" title="2 stars">★</label>

                                                <input type="radio" id="star1" name="rating" value="1" />
                                                <label for="star1" title="1 star">★</label>
                                            </div>
                                            <div id="error-rating" style="color:red; margin-top: 5px;"></div>
                                            <small class="text-muted">Pilih bintang 1 - 5 sesuai dengan tingkat kepuasan Anda.</small>
                                        </div>

                                        <div class="mb-3">
                                            <label for="keterangan" class="form-label">Saran atau Perbaikan (opsional):</label>
                                            <textarea class="form-control" name="keterangan" rows="3" placeholder="Tulis komentar atau saran Anda agar kami dapat memperbaiki layanan...">{{ old('keterangan') }}</textarea>
                                            <small class="text-muted float-end">Kami sangat menghargai masukan Anda.</small>
                                        </div>

                                        <button type="submit" class="btn btn-primary"><i class="bx bx-paper-plane me-2" style="font-size: 1.3rem;"></i>Kirim Penilaian</button>
                                    </form>
                                @else
                                    <h5>Riwayat Penilaian:</h5>
                                    <p>Terima kasih telah menggunakan layanan kami. Kami sangat menghargai masukan Anda.</p>
                                    <ul class="list-group mb-3">
                                        <li class="list-group-item">
                                            <label class="form-label">Rating Kepuasan Anda:</label>
                                            @for ($i = 1; $i <= 5; $i++)
                                                <span style="color: {{ $i <= $dataPengajuan->surveykepuasan->rating ? '#f5b301' : '#ddd' }};">★</span>
                                            @endfor
                                            <br>
                                            <small><em>{{ $dataPengajuan->surveykepuasan->keterangan ?? '-' }}</em></small><br>
                                            <small class="text-muted">Dikirim pada: {{ $dataPengajuan->surveykepuasan->created_at->format('d M Y H:i') }}</small>
                                        </li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row align-items-stretch mb-5">
                <div class="col-md-7">
                    <div class="card mb-4 shadow-sm h-100 border-0">
                        <div class="card-header d-flex justify-content-between align-items-center pb-3 border-bottom">
                            <h5 class="card-title mb-0 fw-bold d-flex align-items-center">
                                <i class="bx bx-user me-2" style="font-size: 1.3rem;"></i>
                                Data Pemohon
                            </h5>
                            @if($dataPengajuan->id_tahapan != 10 )
                                <a href="{{ route('pengajuanruangan') }}" class="btn btn-sm btn-secondary">
                                    <i class="bx bx-arrow-back"></i>&nbsp;Kembali
                                </a>
                            @endif
                        </div>
                        <div class="card-body pt-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="fw-semibold small text-secondary mb-3">Nama Pengaju </div>
                                    <div class="fs-6 text-dark">{{ $dataPengajuan->nama_pengaju }}</div>
                                </div>

                                <div class="col-md-6">
                                    <div class="fw-semibold small text-secondary mb-3">Nomor Kartu ID (NRP/KTP) </div>
                                    <div class="fs-6 text-dark">{{ $dataPengajuan->kartu_id }}</div>
                                </div>

                                <div class="col-md-6">
                                    <div class="fw-semibold small text-secondary mb-3">No. Hp </div>
                                    <div class="fs-6 text-dark">{{ $dataPengajuan->no_hp }}</div>
                                </div>

                                <div class="col-md-6">
                                    <div class="fw-semibold small text-secondary mb-3">Email </div>
                                    <div class="fs-6 text-dark">{{ $dataPengajuan->email }}</div>
                                </div>

                                <div class="col-md-6">
                                    <div class="fw-semibold small text-secondary mb-3">Email ITS </div>
                                    <div class="fs-6 text-dark">{{ $dataPengajuan->email_its }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="fw-semibold small text-secondary mb-3">Status Pengaju </div>
                                    <div class="fs-6 text-dark">{{ $dataPengajuan->statuspengaju->nama }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="fw-semibold small text-secondary mb-1">File Kartu ID (NRP/KTP) </div>
                                    @php
                                        $file = $dataPengajuan->pihakpengaju->file_kartuid;
                                        $filePath = $dataPengajuan->pihakpengaju->files->location;
                                        $imageUrl2 = Storage::disk('private')->exists($filePath)
                                            ? route('file.getprivatefile', $file)
                                            : asset('assets/img/no_image.jpg');
                                    @endphp
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $imageUrl2 }}" class="rounded border shadow-sm" style="height: 80px; object-fit: cover;">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                data-bs-target="#modals-transparent">
                                            Lihat file
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5 d-flex flex-column">
                    <div class="card flex-fill d-flex flex-column">
                        <div class="card-header d-flex justify-content-between align-items-center pb-4 border-bottom">
                            <h5 class="card-title mb-0 fw-bold d-flex align-items-center"><i class="bx bx-history" style="font-size: 1.3rem;"></i>&nbsp;Histori Persetujuan</h5>
                        </div>
                        <div class="card-body overflow-auto p-0" style="flex:1; min-height:0;">
                            <div class="nav-align-top nav-tabs-shadow h-100">
                                <ul class="nav nav-tabs w-100" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button type="button" class="nav-link {{ empty($sudahPengembalian) ? 'active':'' }}" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-home" aria-controls="navs-top-home" aria-selected="true">Peminjaman</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button type="button" class="nav-link {{ !empty($sudahPengembalian) ? 'active':'' }}" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-profile" aria-controls="navs-top-profile" aria-selected="false" tabindex="-1">Pengembalian</button>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade {{ empty($sudahPengembalian) ? 'active show':'' }}" id="navs-top-home" role="tabpanel">
                                        <ul class="timeline pb-0 mb-0">
                                            <li class="timeline-item timeline-item-transparent border-success">
                                                <span class="timeline-point"><i class="bx bx-check-circle text-success"></i></span>
                                                <div class="timeline-event">
                                                    <div class="timeline-header">
                                                        <h6 class="mb-0">Draft</h6>
                                                        <small class="text-muted">{{ $dataPengajuan->created_at->translatedFormat('d F Y h:i') }}</small>
                                                    </div>
                                                    <p class="mt-3">Dibuat oleh {{ $dataPengajuan->pihakpengaju->name }}</p>
                                                </div>
                                            </li>
                                            <li class="timeline-item timeline-item-transparent {{ empty($adminSudahSetuju) ? 'border-left-dashed' : ($adminSudahSetuju->id_statuspersetujuan == 1 ? 'border-success' : 'border-danger') }}">
                                                @if(empty($adminSudahSetuju))
                                                    <span class="timeline-point timeline-point-secondary"></span>
                                                @else
                                                    @if($adminSudahSetuju->id_statuspersetujuan == 1)
                                                        <span class="timeline-point"><i class="bx bx-check-circle text-success"></i></span>
                                                    @else
                                                        <span class="timeline-point"><i class="bx bx-x-circle text-danger"></i></span>
                                                    @endif
                                                @endif
                                                <div class="timeline-event">
                                                    <div class="timeline-header">
                                                        <h6 class="mb-0">Verifikasi Admin Ruang</h6>
                                                        @if(!empty($adminSudahSetuju))
                                                            <small class="text-muted">{{ $adminSudahSetuju->created_at->translatedFormat('d F Y h:i') }}</small>
                                                        @endif
                                                    </div>
                                                    @if(!empty($adminSudahSetuju))
                                                        @if($adminSudahSetuju->id_statuspersetujuan == 1)
                                                            <p class="mt-3 mb-3">Diverifikasi oleh {{ $adminSudahSetuju->nama_penyetuju }}</p>
                                                        @else
                                                            <p class="mt-3 mb-3">Ditolak oleh {{ $adminSudahSetuju->nama_penyetuju }}</p>
                                                            <p class="small fst-italic">{{ $adminSudahSetuju->keterangan }}</p>
                                                        @endif
                                                    @endif
                                                </div>
                                            </li>
                                            <li class="timeline-item timeline-item-transparent {{ empty($pemeriksaAwalSudahSetuju) ? 'border-left-dashed' : ($pemeriksaAwalSudahSetuju->id_statuspersetujuan == 1 ? 'border-success' : 'border-danger') }}">
                                                @if(empty($pemeriksaAwalSudahSetuju))
                                                    <span class="timeline-point timeline-point-secondary"></span>
                                                @else
                                                    @if($pemeriksaAwalSudahSetuju->id_statuspersetujuan == 1)
                                                        <span class="timeline-point"><i class="bx bx-check-circle text-success"></i></span>
                                                    @else
                                                        <span class="timeline-point"><i class="bx bx-x-circle text-danger"></i></span>
                                                    @endif
                                                @endif
                                                <div class="timeline-event">
                                                    <div class="timeline-header">
                                                        <h6 class="mb-0">Pemeriksaan Awal</h6>
                                                        @if(!empty($pemeriksaAwalSudahSetuju))
                                                            <small class="text-muted">{{ $pemeriksaAwalSudahSetuju->created_at->translatedFormat('d F Y h:i') }}</small>
                                                        @endif
                                                    </div>
                                                    @if(!empty($pemeriksaAwalSudahSetuju))
                                                        @if($pemeriksaAwalSudahSetuju->id_statuspersetujuan == 1)
                                                            <p class="mt-3 mb-3">Diperiksa oleh {{ $pemeriksaAwalSudahSetuju->nama_penyetuju }}</p>
                                                        @else
                                                            <p class="mt-3 mb-3">Ditolak oleh {{ $pemeriksaAwalSudahSetuju->nama_penyetuju }}</p>
                                                            <p class="small fst-italic">{{ $pemeriksaAwalSudahSetuju->keterangan }}</p>
                                                        @endif
                                                    @endif
                                                </div>
                                            </li>
                                            <li class="timeline-item timeline-item-transparent {{ empty($kasubbagSudahSetuju) ? 'border-left-dashed' : ($kasubbagSudahSetuju->id_statuspersetujuan == 1 ? 'border-success' : 'border-danger') }}">
                                                @if(empty($kasubbagSudahSetuju))
                                                    <span class="timeline-point timeline-point-secondary"></span>
                                                @else
                                                    @if($kasubbagSudahSetuju->id_statuspersetujuan == 1)
                                                        <span class="timeline-point"><i class="bx bx-check-circle text-success"></i></span>
                                                    @else
                                                        <span class="timeline-point"><i class="bx bx-x-circle text-danger"></i></span>
                                                    @endif
                                                @endif
                                                <div class="timeline-event">
                                                    <div class="timeline-header">
                                                        <h6 class="mb-0">Verifikasi Kasubbag</h6>
                                                        @if(!empty($kasubbagSudahSetuju))
                                                            <small class="text-muted">{{ $kasubbagSudahSetuju->created_at->translatedFormat('d F Y h:i') }}</small>
                                                        @endif
                                                    </div>
                                                    @if(!empty($kasubbagSudahSetuju))
                                                        @if($kasubbagSudahSetuju->id_statuspersetujuan == 1)
                                                            <p class="mt-3 mb-3">Diverifikasi oleh {{ $kasubbagSudahSetuju->nama_penyetuju }}</p>
                                                        @else
                                                            <p class="mt-3 mb-3">Ditolak oleh {{ $kasubbagSudahSetuju->nama_penyetuju }}</p>
                                                            <p class="small fst-italic">{{ $kasubbagSudahSetuju->keterangan }}</p>
                                                        @endif
                                                    @endif
                                                </div>
                                            </li>
                                            <li class="timeline-item timeline-item-transparent border-transparent">
                                                @if(empty($kadepSudahSetuju))
                                                    <span class="timeline-point timeline-point-secondary"></span>
                                                @else
                                                    @if($kadepSudahSetuju->id_statuspersetujuan == 1)
                                                        <span class="timeline-point"><i class="bx bx-check-circle text-success"></i></span>
                                                    @else
                                                        <span class="timeline-point"><i class="bx bx-x-circle text-danger"></i></span>
                                                    @endif
                                                @endif
                                                <div class="timeline-event">
                                                    <div class="timeline-header">
                                                        <h6 class="mb-0">Verifikasi Kadep</h6>
                                                        @if(!empty($kadepSudahSetuju))
                                                            <small class="text-muted">{{ $kadepSudahSetuju->created_at->translatedFormat('d F Y h:i') }}</small>
                                                        @endif
                                                    </div>
                                                    @if(!empty($kadepSudahSetuju))
                                                        @if($kadepSudahSetuju->id_statuspersetujuan == 1)
                                                            <p class="mt-3 mb-3">Diverifikasi oleh {{ $kadepSudahSetuju->nama_penyetuju }}</p>
                                                        @else
                                                            <p class="mt-3 mb-3">Ditolak oleh {{ $kadepSudahSetuju->nama_penyetuju }}</p>
                                                            <p class="small fst-italic">{{ $kadepSudahSetuju->keterangan }}</p>
                                                        @endif
                                                    @endif
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="tab-pane fade {{ !empty($sudahPengembalian) ? 'active show':'' }}" id="navs-top-profile" role="tabpanel">
                                        <ul class="timeline pb-0 mb-0">
                                            <li class="timeline-item timeline-item-transparent {{ empty($sudahPengembalian) ? 'border-left-dashed' : ($sudahPengembalian->id_statuspersetujuan == 1 ? 'border-success' : 'border-danger') }}">
                                                @if(empty($sudahPengembalian))
                                                    <span class="timeline-point timeline-point-secondary"></span>
                                                @else
                                                    @if($sudahPengembalian->id_statuspersetujuan == 1)
                                                        <span class="timeline-point"><i class="bx bx-check-circle text-success"></i></span>
                                                    @else
                                                        <span class="timeline-point"><i class="bx bx-x-circle text-danger"></i></span>
                                                    @endif
                                                @endif
                                                <div class="timeline-event">
                                                    <div class="timeline-header">
                                                        <h6 class="mb-0">Pengembalian</h6>
                                                        @if(!empty($sudahPengembalian))
                                                            <small class="text-muted">{{ $sudahPengembalian->created_at->translatedFormat('d F Y h:i') }}</small>
                                                        @endif
                                                    </div>
                                                    @if(!empty($sudahPengembalian))
                                                        @if($sudahPengembalian->id_statuspersetujuan == 1)
                                                            <p class="mt-3 mb-3">Diajukan oleh {{ $sudahPengembalian->nama_penyetuju }}</p>
                                                        @else
                                                            <p class="mt-3 mb-3">Ditolak oleh {{ $sudahPengembalian->nama_penyetuju }}</p>
                                                            <p class="small fst-italic">{{ $sudahPengembalian->keterangan }}</p>
                                                        @endif
                                                    @endif
                                                </div>
                                            </li>
                                            <li class="timeline-item timeline-item-transparent {{ empty($adminVerifikasiPengembalian) ? 'border-left-dashed' : ($adminVerifikasiPengembalian->id_statuspersetujuan == 1 ? 'border-success' : 'border-danger') }}">
                                                @if(empty($adminVerifikasiPengembalian))
                                                    <span class="timeline-point timeline-point-secondary"></span>
                                                @else
                                                    @if($adminVerifikasiPengembalian->id_statuspersetujuan == 1)
                                                        <span class="timeline-point"><i class="bx bx-check-circle text-success"></i></span>
                                                    @else
                                                        <span class="timeline-point"><i class="bx bx-x-circle text-danger"></i></span>
                                                    @endif
                                                @endif
                                                <div class="timeline-event">
                                                    <div class="timeline-header">
                                                        <h6 class="mb-0">Verifikasi Admin Ruang</h6>
                                                        @if(!empty($adminVerifikasiPengembalian))
                                                            <small class="text-muted">{{ $adminVerifikasiPengembalian->created_at->translatedFormat('d F Y h:i') }}</small>
                                                        @endif
                                                    </div>
                                                    @if(!empty($adminVerifikasiPengembalian))
                                                        @if($adminVerifikasiPengembalian->id_statuspersetujuan == 1)
                                                            <p class="mt-3 mb-3">Diverifikasi oleh {{ $adminVerifikasiPengembalian->nama_penyetuju }}</p>
                                                        @else
                                                            <p class="mt-3 mb-3">Ditolak oleh {{ $adminVerifikasiPengembalian->nama_penyetuju }}</p>
                                                            <p class="small fst-italic">{{ $adminVerifikasiPengembalian->keterangan }}</p>
                                                        @endif
                                                    @endif
                                                </div>
                                            </li>
                                            <li class="timeline-item timeline-item-transparent {{ empty($pemeriksaAkhirSudahSetuju) ? 'border-left-dashed' : ($pemeriksaAkhirSudahSetuju->id_statuspersetujuan == 1 ? 'border-success' : 'border-danger') }}">
                                                @if(empty($pemeriksaAkhirSudahSetuju))
                                                    <span class="timeline-point timeline-point-secondary"></span>
                                                @else
                                                    @if($pemeriksaAkhirSudahSetuju->id_statuspersetujuan == 1)
                                                        <span class="timeline-point"><i class="bx bx-check-circle text-success"></i></span>
                                                    @else
                                                        <span class="timeline-point"><i class="bx bx-x-circle text-danger"></i></span>
                                                    @endif
                                                @endif
                                                <div class="timeline-event">
                                                    <div class="timeline-header">
                                                        <h6 class="mb-0">Pemeriksa Akhir</h6>
                                                        @if(!empty($pemeriksaAkhirSudahSetuju))
                                                            <small class="text-muted">{{ $pemeriksaAkhirSudahSetuju->created_at->translatedFormat('d F Y h:i') }}</small>
                                                        @endif
                                                    </div>
                                                    @if(!empty($pemeriksaAkhirSudahSetuju))
                                                        @if($pemeriksaAkhirSudahSetuju->id_statuspersetujuan == 1)
                                                            <p class="mt-3 mb-3">Diperiksa oleh {{ $pemeriksaAkhirSudahSetuju->nama_penyetuju }}</p>
                                                        @else
                                                            <p class="mt-3 mb-3">Ditolak oleh {{ $pemeriksaAkhirSudahSetuju->nama_penyetuju }}</p>
                                                            <p class="small fst-italic">{{ $pemeriksaAkhirSudahSetuju->keterangan }}</p>
                                                        @endif
                                                    @endif
                                                </div>
                                            </li>
                                            <li class="timeline-item timeline-item-transparent {{ empty($sudahVerifikasiPengembalian) ? 'border-left-dashed' : ($sudahVerifikasiPengembalian->id_statuspersetujuan == 1 ? 'border-success' : 'border-danger') }}">
                                                @if(empty($sudahVerifikasiPengembalian))
                                                    <span class="timeline-point timeline-point-secondary"></span>
                                                @else
                                                    @if($sudahVerifikasiPengembalian->id_statuspersetujuan == 1)
                                                        <span class="timeline-point"><i class="bx bx-check-circle text-success"></i></span>
                                                    @else
                                                        <span class="timeline-point"><i class="bx bx-x-circle text-danger"></i></span>
                                                    @endif
                                                @endif
                                                <div class="timeline-event">
                                                    <div class="timeline-header">
                                                        <h6 class="mb-0">Verifikasi Kasubbag</h6>
                                                        @if(!empty($sudahVerifikasiPengembalian))
                                                            <small class="text-muted">{{ $sudahVerifikasiPengembalian->created_at->translatedFormat('d F Y h:i') }}</small>
                                                        @endif
                                                    </div>
                                                    @if(!empty($sudahVerifikasiPengembalian))
                                                        @if($sudahVerifikasiPengembalian->id_statuspersetujuan == 1)
                                                            <p class="mt-3 mb-3">Diverifikasi oleh {{ $sudahVerifikasiPengembalian->nama_penyetuju }}</p>
                                                        @else
                                                            <p class="mt-3 mb-3">Ditolak oleh {{ $sudahVerifikasiPengembalian->nama_penyetuju }}</p>
                                                            <p class="small fst-italic">{{ $sudahVerifikasiPengembalian->keterangan }}</p>
                                                        @endif
                                                    @endif
                                                </div>
                                            </li>
                                            <li class="timeline-item timeline-item-transparent border-transparent pb-0">
                                                @if(empty($sudahVerifikasiPengembalian))
                                                    <span class="timeline-point timeline-point-secondary"></span>
                                                @else
                                                    @if($sudahVerifikasiPengembalian->id_statuspersetujuan == 1)
                                                        <span class="timeline-point"><i class="bx bx-check-circle text-success"></i></span>
                                                    @else
                                                        <span class="timeline-point"><i class="bx bx-x-circle text-danger"></i></span>
                                                    @endif
                                                @endif
                                                <div class="timeline-event pb-0">
                                                    <div class="timeline-header">
                                                        <h6 class="mb-0">Selesai</h6>
                                                    </div>
                                                    <p class="mt-1 mb-0">Peminjaman Ruangan Sudah Selesai</p>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card" <?= ($statusVerifikasi['must_aprove'] == 'AJUKAN' || $statusVerifikasi['must_aprove'] == 'PENGEMBALIAN' || $statusVerifikasi['must_aprove'] == 'VERIFIKASI') ? 'style="margin-bottom: 5.5rem !important;"':'style="margin-bottom: 1.5rem !important;"' ?> >
                <div class="card-header d-flex align-items-center pb-4 border-bottom">
                    <h5 class="card-title mb-0 fw-bold d-flex align-items-center"><i class="bx bx-building pb-0" style="font-size: 1.3rem;"></i>&nbsp;Data Pengajuan Ruangan</h5>
                    @if(!empty($kadepSudahSetuju))
                        @if($kadepSudahSetuju->id_statuspersetujuan == 1)
                            &nbsp;<a href="{{ route('pengajuanruangan.bapeminjaman', $idPengajuan) }}" target="_blank" class="btn btn-sm btn-outline-success" style="margin-left: 1rem"><span class="bx bx-download me-2 "></span>Berita Acara Peminjaman</a>
                        @endif
                    @endif
                    @if($dataPengajuan->id_tahapan == 10)
                        &nbsp;<a href="{{ route('pengajuanruangan.bapengembalian', $idPengajuan) }}" target="_blank" class="btn btn-sm btn-outline-primary" style="margin-left: 1rem"><span class="bx bx-download me-2 "></span>Berita Acara Pengembalian</a>
                    @endif
                </div>
                <div class="card-body pt-4">
                    <form id="frmPengajuanRuang" method="POST" action="{{ route('pengajuanruangan.doupdate') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_pengajuan" value="{{ $idPengajuan }}">
                        <input type="hidden" name="id_akses" id="id_aksespersetujuan">
                        <input type="hidden" name="tahapan_next" id="tahapan_next">
                        <div class="row g-6">
                            <div class="col-sm-6">
                                <div class="fw-semibold small text-secondary mb-3">Status Pengajuan</div>
                                <div class="fs-6 text-mute">
                                    <span class="fw-bold small">{{ $dataPengajuan->tahapanpengajuan->nama }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="fw-semibold small text-secondary mb-3">Jadwal Peminjaman </div>
                                <div class="fs-6 text-dark fst-italic">{!! $jadwalPeminjaman !!}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="fw-semibold small text-secondary mb-3">Nama Kegiatan </div>
                                <div class="fs-6 text-dark"><span class="small text-dark">{{ $dataPengajuan->nama_kegiatan }}</span></div>
                            </div>
                            <div class="col-sm-6">
                                <div class="fw-semibold small text-secondary mb-3">Deskripsi Kegiatan </div>
                                <div class="fs-6 text-muted small fst-italic">{{ $dataPengajuan->deskripsi }}</div>
{{--                                <textarea class="form-control" rows="5" disabled>{{ $dataPengajuan->deskripsi }}</textarea>--}}
                            </div>
                            <div class="col-sm-6">
                                <div class="fw-semibold small text-secondary mb-3">Petugas Pemeriksa Awal </div>
                                <div class="fs-6">
                                    @if(!empty($dataPengajuan->pemeriksaawal))
                                        <span class="small text-dark">{{ $dataPengajuan->pemeriksaawal->name }}</span>
                                    @else
                                        @if($statusVerifikasi['must_aprove'] == 'VERIFIKASI' && $dataPengajuan->id_tahapan == 2)
                                            <div>
                                                <select name="pemeriksa_awal" id="pemeriksa_awal" class="form-control" required></select>
                                                <div class="error-container" id="error-pemeriksa_awal"></div>
                                            </div>
                                        @else
                                            <span class="fst-italic text-danger small">Belum Ditentukan</span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="fw-semibold small text-secondary mb-3">Petugas Pemeriksa Akhir </div>
                                <div class="fs-6">
                                    @if(!empty($dataPengajuan->pemeriksaakhir))
                                        <span class="small text-dark">{{ $dataPengajuan->pemeriksaakhir->name }}</span>
                                    @else
                                        @if($statusVerifikasi['must_aprove'] == 'VERIFIKASI' && $dataPengajuan->id_tahapan == 7)
                                            <div>
                                                <select name="pemeriksa_akhir" id="pemeriksa_akhir" class="form-control" required></select>
                                                <div class="error-container" id="error-pemeriksa_akhir"></div>
                                            </div>
                                        @else
                                            <span class="fst-italic text-danger small">Belum Ditentukan</span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="fw-semibold small text-secondary mb-3">Ruangan Dipinjam </div>
                                <div class="fs-6 text-dark d-flex flex-wrap gap-1">
                                    {!! $dataPengajuan->pengajuanruangandetail->map(function($ruang) {
                                        return '<span class="badge bg-primary rounded-pill">'
                                            . $ruang->ruangan->kode_ruangan . ' - ' . $ruang->ruangan->nama .
                                        '</span>';
                                    })->implode(' ') !!}
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="fw-semibold small text-secondary mb-3">Data Rincian Peralatan </div>
                                <div class="table-responsive" id="tabelPeminjaman">
                                    <table class="table table-bordered table-sm small">
                                        <thead>
                                        <tr style="background-color: rgba(8, 60, 132, 0.16) !important">
                                            <td class="fw-bold" nowrap="" style="width: 5%; color: rgb(8, 60, 132)" align="center">No</td>
                                            <td class="fw-bold" style="width: 30%; color: rgb(8, 60, 132)" align="center">Nama Peralatan</td>
                                            <td class="fw-bold" nowrap="" style="width: 5%; color: rgb(8, 60, 132)" align="center">Jumlah</td>
                                            <td class="fw-bold" nowrap="" style="width: 5%; color: rgb(8, 60, 132)" align="center">Status Awal</td>
                                            <td class="fw-bold" nowrap="" style="width: 25%; color: rgb(8, 60, 132)" align="center">Keterangan Awal</td>
                                            <td class="fw-bold" nowrap="" style="width: 5%; color: rgb(8, 60, 132)" align="center">Status Akhir</td>
                                            <td class="fw-bold" nowrap="" style="width: 25%; color: rgb(8, 60, 132)" align="center">Keterangan Akhir</td>
                                        </tr>
                                        </thead>
                                        <tbody id="tbodySarpras">

                                        @foreach($dataPengajuan->pengajuanperalatandetail as $key => $peralatan)
                                            <tr>
                                                <td align="center">{{ $key+1 }}</td>
                                                <td class="text-dark">{{ $peralatan->nama_sarana }}</td>
                                                <td align="center">{{ $peralatan->jumlah }}</td>
                                                <td align="center" class="text-nowrap">
                                                    @if($statusVerifikasi['must_aprove'] == 'VERIFIKASI' && $dataPengajuan->id_tahapan == 3)
                                                        <div class="d-flex gap-3">
                                                            <div class="form-check form-check-inline form-check-success">
                                                                <input class="form-check-input" type="radio" name="kondisiawal{{ $peralatan->id_pengajuanperalatan_ruang }}" id="kondisiawalada{{ $peralatan->id_pengajuanperalatan_ruang }}" value="1" required>
                                                                <label class="form-check-label" for="kondisiawalada{{ $peralatan->id_pengajuanperalatan_ruang }}">Ada</label>
                                                            </div>
                                                            <div class="form-check form-check-inline form-check-success">
                                                                <input class="form-check-input" type="radio" name="kondisiawal{{ $peralatan->id_pengajuanperalatan_ruang }}" id="kondisiawaltidak{{ $peralatan->id_pengajuanperalatan_ruang }}" value="-1" required>
                                                                <label class="form-check-label" for="kondisiawaltidak{{ $peralatan->id_pengajuanperalatan_ruang }}">Tidak</label>
                                                            </div>
                                                        </div>
                                                    @else
                                                        @if(empty($peralatan->is_valid_awal))
                                                            -
                                                        @else
                                                            @if($peralatan->is_valid_awal == 1)
                                                                <span class="bx bx-check text-success"></span>
                                                            @else
                                                                <span class="bx bx-x text-danger"></span>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($statusVerifikasi['must_aprove'] == 'VERIFIKASI' && $dataPengajuan->id_tahapan == 3)
                                                        <textarea name="keterangan{{ $peralatan->id_pengajuanperalatan_ruang }}" class="form-control" id="keterangan{{ $peralatan->id_pengajuanperalatan_ruang }}" rows="2" required></textarea>
                                                    @else
                                                        <span class="text-muted fst-italic small">{{ $peralatan->keterangan_awal }}</span>
                                                    @endif
                                                </td>
                                                <td align="center">
                                                    @if($statusVerifikasi['must_aprove'] == 'VERIFIKASI' && $dataPengajuan->id_tahapan == 8)
                                                        <div class="d-flex gap-3">
                                                            <div class="form-check form-check-inline form-check-success">
                                                                <input class="form-check-input" type="radio" name="kondisiakhir{{ $peralatan->id_pengajuanperalatan_ruang }}" id="kondisiakhirada{{ $peralatan->id_pengajuanperalatan_ruang }}" value="1" required>
                                                                <label class="form-check-label" for="kondisiakhirada{{ $peralatan->id_pengajuanperalatan_ruang }}">Ada</label>
                                                            </div>
                                                            <div class="form-check form-check-inline form-check-success">
                                                                <input class="form-check-input" type="radio" name="kondisiakhir{{ $peralatan->id_pengajuanperalatan_ruang }}" id="kondisiakhirtidak{{ $peralatan->id_pengajuanperalatan_ruang }}" value="-1" required>
                                                                <label class="form-check-label" for="kondisiakhirtidak{{ $peralatan->id_pengajuanperalatan_ruang }}">Tidak</label>
                                                            </div>
                                                        </div>
                                                    @else
                                                        @if(empty($peralatan->is_valid_akhir))
                                                            -
                                                        @else
                                                            @if($peralatan->is_valid_akhir == 1)
                                                                <span class="bx bx-check text-success"></span>
                                                            @else
                                                                <span class="bx bx-x text-danger"></span>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($statusVerifikasi['must_aprove'] == 'VERIFIKASI' && $dataPengajuan->id_tahapan == 8)
                                                        <textarea name="keterangan{{ $peralatan->id_pengajuanperalatan_ruang }}" class="form-control" id="keterangan{{ $peralatan->id_pengajuanperalatan_ruang }}" rows="2" required></textarea>
                                                    @else
                                                        <span class="text-muted fst-italic small">{{ $peralatan->keterangan_akhir }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @if(!empty($kadepSudahSetuju))
                                @if($kadepSudahSetuju->id_statuspersetujuan == 1)
                                    <div class="col-sm-12">
                                        <div class="fw-semibold small text-secondary mb-3">Kondisi Ruangan dan Peralatan Sesudah Acara <i>(foto minimal 5, dan ukuran maksimal 5 mb)</i></div>
                                            @if($dataPengajuan->id_tahapan == 6)
                                                <input type="file" class="form-control" name="filesesudahacara[]" id="filesesudahacara" accept="image/*" multiple autofocus>
                                            @else
                                                @if($dataPengajuan->filepengajuanruangan && $dataPengajuan->filepengajuanruangan->count() > 0)
                                                    <div class="row g-3">
                                                        @foreach($dataPengajuan->filepengajuanruangan as $file)
                                                            @php
                                                                $filePath = $file->file->location ?? null;
                                                                $imageUrl = $filePath && Storage::disk('public')->exists($filePath)
                                                                    ? route('file.getpublicfile', $file->file->id_file)
                                                                    : asset('assets/img/no_image.jpg');
                                                            @endphp
                                                            <div class="col-6 col-md-4 col-lg-3">
                                                                <div class="card shadow-sm h-100">
                                                                    <img src="{{ $imageUrl }}"
                                                                         class="card-img-top img-fluid"
                                                                         alt="Foto Sesudah Acara"
                                                                         style="object-fit: cover; height: 180px;">
                                                                    <div class="card-body p-2 text-center">
                                                                        <a href="{{ $imageUrl }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                            Lihat
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                <div class="text-muted small mt-2">Belum ada foto yang diunggah.</div>
                                            @endif
                                        </div>
                                    @endif
                                @endif
                            @endif
                        </div>
                        <ul class="fa-ul ml-auto float-end mt-5">
                            <li>
                                <small><em>Data tidak bisa diupdate, Silahkan <b>hapus pengajuan</b> dan input kembali data untuk memperbaiki selama pengajuan masih belum <b>Diajukan</b>.</em></small>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
            @if($statusVerifikasi['must_aprove'] == 'AJUKAN' || $statusVerifikasi['must_aprove'] == 'PENGEMBALIAN' || $statusVerifikasi['must_aprove'] == 'VERIFIKASI')
                <div class="position-fixed bottom-0 mb-10 pb-3" style="z-index: 1050;">
                    <div class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached" style="top: auto; bottom: 4.5rem; padding: 0;">
                        <div class="card rounded-3 w-100 bg-gray-500 border-gray-700" style="box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <!-- Isi card -->
                                <div class="d-flex align-items-center">
                                    @if($statusVerifikasi['must_aprove'] == 'AJUKAN')
                                        <div class="bg-warning rounded me-3" style="width: 10px; height: 50px;"></div>
                                        <p class="mb-0 fw-medium">Pengajuan Belum Diajukan!</p>
                                    @elseif($statusVerifikasi['must_aprove'] == 'PENGEMBALIAN')
                                        <div class="bg-warning rounded me-3" style="width: 10px; height: 50px;"></div>
                                        <p class="mb-0 fw-medium">Kembalikan Peminjaman Ruangan!</p>
                                    @elseif($statusVerifikasi['must_aprove'] == 'VERIFIKASI')
                                        <div class="bg-danger rounded me-3" style="width: 10px; height: 50px;"></div>
                                        <p class="mb-0 fw-medium text-danger">Pengajuan Belum Diverifikasi!</p>
                                    @else
                                        @if($statusVerifikasi['data'])
                                            <div class="bg-info rounded me-3" style="width: 10px; height: 50px;"></div>
                                            <p class="mb-0 fw-medium">{{ $statusVerifikasi['data']->statuspersetujuan->nama.' oleh '.$statusVerifikasi['data']->nama_penyetuju.' pada '.$statusVerifikasi['data']->created_at->format('d/m/Y H:i') }}</p>
                                        @else
                                            <div class="bg-danger rounded me-3" style="width: 10px; height: 50px;"></div>
                                            <p class="mb-0 fw-medium">{{ $statusVerifikasi['message'] }}</p>
                                        @endif
                                    @endif
                                </div>
                                <div class="d-flex align-items-center">
                                    @if($statusVerifikasi['must_aprove'] == 'AJUKAN')
                                        <a href="javascript:void(0)" id="btn-ajukan" data-id_akses_ajukan="{{ $statusVerifikasi['must_akses'] }}" data-tahapan_next="{{ $statusVerifikasi['tahapan_next'] }}" class="btn btn-success btn-sm d-flex align-items-center">
                                            <i class="bx bx-paper-plane"></i>&nbsp;{{ $statusVerifikasi['label_verifikasi'] }}
                                        </a>
                                    @endif
                                        @if($statusVerifikasi['must_aprove'] == 'PENGEMBALIAN')
                                            <a href="javascript:void(0)" id="btn-setujui" data-id_akses_ajukan="{{ $statusVerifikasi['must_akses'] }}" data-tahapan_next="{{ $statusVerifikasi['tahapan_next'] }}" class="btn btn-warning btn-sm d-flex align-items-center">
                                                <i class="bx bx-paper-plane"></i>&nbsp;{{ $statusVerifikasi['label_verifikasi'] }}
                                            </a>
                                        @endif
                                    @if($statusVerifikasi['must_aprove'] == 'VERIFIKASI')
                                        <a href="javascript:void(0)" id="btn-setujui" data-id_akses_ajukan="{{ $statusVerifikasi['must_akses'] }}" data-tahapan_next="{{ $statusVerifikasi['tahapan_next'] }}" class="btn btn-success btn-sm d-flex align-items-center">
                                            <i class="bx bx-check-circle"></i>&nbsp;{{ $statusVerifikasi['label_verifikasi'] }}
                                        </a>
                                        &nbsp;&nbsp;
                                        <a href="javascript:void(0)" data-id_akses_tolak="{{ $statusVerifikasi['must_akses'] }}" data-bs-toggle="modal" data-bs-target="#modal-tolak" class="btn btn-danger btn-sm d-flex align-items-center">
                                            <i class="bx bx-x"></i>&nbsp;Tolak
                                        </a>
                                    @endif
                                    @if(!empty($statusVerifikasi['must_sebagai']))
                                        &nbsp;<br><span class="fst-italic fw-medium">(Sebagai {{ $statusVerifikasi['must_sebagai'] }})</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card mb-6 rounded-3 w-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <!-- Isi card -->
                        <div class="d-flex align-items-center">
                            @if($statusVerifikasi['data'])
                                <div class="{{ $statusVerifikasi['data']->statuspersetujuan->class_bg }} rounded me-3" style="width: 10px; height: 50px;"></div>
                                <p class="mb-0 fw-medium">{{ $statusVerifikasi['data']->statuspersetujuan->nama.' oleh '.$statusVerifikasi['data']->nama_penyetuju.' pada '.$statusVerifikasi['data']->created_at->format('d/m/Y H:i') }}</p>
                            @else
                                <div class="bg-danger rounded me-3" style="width: 10px; height: 50px;"></div>
                                <p class="mb-0 fw-medium">{{ $statusVerifikasi['message'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
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
    <div class="modal fade" id="modal-ajukan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
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
                    <button type="button" id="btn-ajuanconfirm" class="btn btn-success">Iya</button>
                </div>
            </div>
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
    <div class="modal fade" id="modal-hapusfilependukung" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <form action="{{ route('pengajuansurat.hapusfilependukung') }}" method="POST">
                @csrf
                <input type="hidden" name="id_pengajuan" value="{{ $idPengajuan }}" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel2">Hapus File Pendukung</h5>
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
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel2">Verifikasi Pengajuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah yakin verifikasi, pengajuan ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btn-setujuiconfirm" class="btn btn-success">Iya</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-tolak" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <form action="{{ route('pengajuanruangan.tolak') }}" method="POST">
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
        const urlGetUser = '{{ route('pengajuanruangan.getuserpenyetuju') }}';
    </script>
    @vite('resources/views/script_view/pengajuan_ruangan/detail_pengajuan.js')
@endsection
