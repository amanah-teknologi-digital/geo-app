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
            @if($dataPengajuan->id_statuspengajuan == 1 )
                <div class="row align-items-stretch mb-5">
                    <div class="col-md-12">
                        <div class="card mb-4 shadow-sm h-100 border-0">
                            <div class="card-header d-flex justify-content-between align-items-center pb-3 border-bottom">
                                <h5 class="card-title mb-0 fw-bold d-flex align-items-center">
                                    <i class="bx bx-collection me-2" style="font-size: 1.3rem;"></i>
                                    Surver Kepuasan Layanan Kami
                                </h5>

                                <a href="{{ route('pengajuansurat') }}" class="btn btn-sm btn-secondary">
                                    <i class="bx bx-arrow-back"></i>&nbsp;Kembali
                                </a>
                            </div>
                            <div class="card-body pt-4">
                                @if(empty($dataPengajuan->surveykepuasan))
                                    <form id="FrmSurveyKepuasan" action="{{ route('pengajuansurat.surveykepuasan') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id_pengajuan" value="{{ $id_pengajuan }}">

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
                            @if($dataPengajuan->id_statuspengajuan != 1 )
                                <a href="{{ route('pengajuansurat') }}" class="btn btn-sm btn-secondary">
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
                        <div class="card-body pt-4 overflow-auto" style="flex:1; min-height:0;">
                            <div class="row">
                                <div class="col-12">
                                    @if($dataPengajuan->persetujuan->isNotEmpty())
                                        <ul class="timeline-with-icons">
                                            @foreach($dataPengajuan->persetujuan as $pers)
                                                <li class="timeline-item mb-5"><span class="timeline-icon {{ $pers->statuspersetujuan->class_bg }}"><i class="{{ $pers->statuspersetujuan->class_label }}"></i></span>
                                                    <p class="mb-0 fw-medium">
                                                        {{ $pers->statuspersetujuan->nama.' '.(optional($pers->akses)->nama ?? optional($pers->pihakpenyetujupengajuansurat)->nama) }}
                                                    </p>
                                                    <p class="text-muted fst-italic small">
                                                        {{ $pers->created_at->format('d/m/Y H:i') }} oleh {{ $pers->nama_penyetuju }}
                                                    </p>
                                                    @if(!empty($pers->keterangan))
                                                        <p class="text-muted small">
                                                            <b>Keterangan:</b> <span class="fst-italic">{{ $pers->keterangan }}</span>
                                                        </p>
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
                </div>
            </div>
            <div class="card" <?= ($statusVerifikasi['must_aprove'] == 'AJUKAN' || $statusVerifikasi['must_aprove'] == 'SUDAH DIREVISI' || $statusVerifikasi['must_aprove'] == 'VERIFIKASI') ? 'style="margin-bottom: 5.5rem !important;"':'style="margin-bottom: 1.5rem !important;"' ?> >
                <div class="stage-bar bg-primary text-white">
                    {{ $dataPengajuan->statuspengajuan->nama }}
                </div>
                <div class="card-header d-flex justify-content-between align-items-center pb-4 border-bottom">
                    <h5 class="card-title mb-0 fw-bold d-flex align-items-center"><i class="bx bx-envelope pb-0" style="font-size: 1.3rem;"></i>&nbsp;Data Persuratan</h5>
                </div>
                <div class="card-body pt-4">
                    <form id="formPengajuan" method="POST" action="{{ route('pengajuansurat.doupdate') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_pengajuan" value="{{ $id_pengajuan }}" required>
                        <div class="row g-6">
                            <div>
                                <label for="email" class="form-label">Persetujuan</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div id="list-persetujuan" style="font-weight: bold;">
                                        <span class="badge bg-primary text-white">1. Admin {{ $namaLayananSurat }} </span>
                                        @if ($dataPengajuan->pihakpenyetuju->isNotEmpty())
                                            <i class="bx bx-arrow-back text-primary" style="transform: rotate(180deg);"></i>
                                            @foreach ($dataPengajuan->pihakpenyetuju as $p)
                                                <span class="badge bg-primary text-white">{{ $p->urutan }}. {{ $p->nama }} </span> @if (!$loop->last) <i class="bx bx-arrow-back text-primary" style="transform: rotate(180deg);"></i> @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="jenis_surat" class="form-label">Jenis Surat <span class="text-danger">*</span></label>
                                <select id="jenis_surat" class="form-control" required disabled>
                                    <option value="" selected disabled>-- Pilih Jenis Surat --</option>
                                    @foreach($dataJenisSurat as $row)
                                        <option value="{{ $row->id_jenissurat }}" {{ ($dataPengajuan->id_jenissurat == $row->id_jenissurat) ? 'selected':'' }}>{{ $row->nama }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="jenis_surat" value="{{ $dataPengajuan->id_jenissurat }}" >
                            </div>
                            <div>
                                <label for="isi_surat" class="form-label">Form Isi Surat <span class="text-danger">*</span></label>
                                <div id="editor-loading" class="text-center">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <textarea id="editor_surat" name="editor_surat" style="height: 500px;">{!! $dataPengajuan->data_form !!}</textarea>
                                <div class="error-container" id="error-quil"></div>
                            </div>
                            <div>
                                <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
                                <textarea name="keterangan" id="keterangan" class="form-control" cols="10" rows="5" required {{ $isEdit? '':'readonly' }} >{{ $dataPengajuan->keterangan }}</textarea>
                            </div>
                            <div id="div_datapendukung" <?= !empty($dataPengajuan->nama_pendukung) ? '':'style="display: none"' ?>>
                                <label for="data_pendukung" class="form-label">Data Pendukung <span id="nama_datapendukung" class="text-muted fst-italic">{{ !empty($dataPengajuan->nama_pendukung) ? "(".$dataPengajuan->nama_pendukung.")":'' }}</span> <span class="text-danger">*</span></label>
                                @if(!empty($dataPengajuan->id_datapendukung))
                                    @php
                                        $filePath = optional($dataPengajuan->filependukung)->location ?? 'no-exist';
                                        $fileId = optional($dataPengajuan->filependukung)->id_file ?? -1;
                                        $imageUrl = Storage::disk('public')->exists($filePath)
                                            ? route('file.getpublicfile', $fileId)
                                            : false;
                                    @endphp
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <a href="{{ $imageUrl }}" target="_blank">
                                            <div class="d-flex align-items-center gap-2 flex-wrap"><span class="text-success small fw-semibold">
                                                    <i class="bx bxs-file-archive me-1"></i>{{ $dataPengajuan->filependukung->file_name }}</span>
                                                <i class="small text-secondary">(<span >{{ formatBytes($dataPengajuan->filependukung->file_size) }}</span>)</i>
                                            </div>
                                        </a>
                                        @if($isEdit)
                                            <span class="bx bx-x text-danger cursor-pointer" data-id_file="{{ $dataPengajuan->filependukung->id_file }}" data-bs-toggle="modal" data-bs-target="#modal-hapusfilependukung"></span>
                                        @endif
                                    </div>
                                @else
                                    <input type="file" class="form-control" name="data_pendukung" id="data_pendukung" accept="application/pdf" {{ !empty($dataPengajuan->nama_pendukung) ? 'required':'' }}>
                                @endif
                            </div>
                            @if($dataPengajuan->filesurat->isNotEmpty())
                                <div>
                                    <label for="filehasil" class="form-label fw-bold">File Surat: </label>
                                    @foreach($dataPengajuan->filesurat as $file)
                                        @php
                                            $filePath = optional($file->file)->location ?? 'no-exist';
                                            $fileId = optional($file->file)->id_file ?? -1;
                                            $imageUrl = Storage::disk('public')->exists($filePath)
                                                ? route('file.getpublicfile', $fileId)
                                                : false;
                                        @endphp
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <a href="{{ $imageUrl }}" target="_blank">
                                                <div class="d-flex align-items-center gap-2 flex-wrap"><span class="text-success small fw-semibold">
                                                    <i class="bx bxs-file-archive me-1"></i>{{ $file->file->file_name }}</span>
                                                    <i class="small text-secondary">(<span >{{ formatBytes($file->file->file_size) }}</span>)</i>
                                                </div>
                                            </a>
                                            @if(in_array($idAkses, [1,2]) OR $userTambahan)
                                                <span class="bx bx-x text-danger cursor-pointer" data-id_file="{{ $file->file->id_file }}" data-bs-toggle="modal" data-bs-target="#modal-hapusfile"></span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        @if($isEdit)
                            <div class="mt-6">
                                <button type="submit" class="btn btn-warning me-3 text-black"><i class="bx bx-save"></i>&nbsp;Update Pengajuan</button>
                            </div>
                        @endif
                    </form>
                    @if((in_array($idAkses, [1,2]) OR $userTambahan) && $dataPengajuan->id_statuspengajuan == 1)
                        <div class="mt-6 mb-10">
                            <label for="uploadhasilsurat" class="form-label">Upload Hasil Surat <i class="text-muted fw-bold">(Opsional & bisa lebih dari 1, PDF Max 5 MB)</i></label>
                            <form id="frmUploadFile" action="{{ route('pengajuansurat.uploadfile') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id_pengajuan" value="{{ $id_pengajuan }}" >
                                <div class="input-group">
                                    <input type="file" class="form-control" name="filesuratupload[]" id="filesuratupload" accept="application/pdf" multiple required>
                                    <button class="btn btn-outline-primary" type="submit"><i class="bx bx-save"></i>&nbsp;Upload File</button>
                                </div>
                                <div class="error-container" id="error-uploadfile"></div>
                            </form>
                        </div>
                    @endif

                    <ul class="fa-ul ml-auto float-end mt-5">
                        <li>
                            <small><em>Ganti text yang <b>bewarna kuning</b> sesuai data yang akan diajukan!.</em></small>
                        </li>
                        <li>
                            <small><em>Jika ada <b>revisi dari admin</b>, maka update data <b>pengajuan</b> atau <b>biodata</b> sesuai dengan <b>arahan revisi</b> pada histori persetujuan.</em></small>
                        </li>
                    </ul>
                </div>
            </div>
            @if($statusVerifikasi['must_aprove'] == 'AJUKAN' || $statusVerifikasi['must_aprove'] == 'SUDAH DIREVISI' || $statusVerifikasi['must_aprove'] == 'VERIFIKASI')
                <div class="position-fixed bottom-0 mb-10 pb-3" style="z-index: 1050;">
                    <div class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached" style="top: auto; bottom: 4.5rem; padding: 0;">
                        <div class="card rounded-3 w-100 bg-gray-500 border-gray-700" style="box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <!-- Isi card -->
                                <div class="d-flex align-items-center">
                                    @if($statusVerifikasi['must_aprove'] == 'AJUKAN')
                                        <div class="bg-warning rounded me-3" style="width: 10px; height: 50px;"></div>
                                        <p class="mb-0 fw-medium">Pengajuan Belum Diajukan!</p>
                                    @elseif($statusVerifikasi['must_aprove'] == 'SUDAH DIREVISI')
                                        <div class="bg-warning rounded me-3" style="width: 10px; height: 50px;"></div>
                                        <p class="mb-0 fw-medium">Pengajuan Direvisi!</p>
                                    @elseif($statusVerifikasi['must_aprove'] == 'VERIFIKASI')
                                        <div class="bg-danger rounded me-3" style="width: 10px; height: 50px;"></div>
                                        @if($dataPengajuan->id_statuspengajuan == 5)
                                            <p class="mb-0 fw-medium text-danger">Pengajuan sudah direvisi dan belum diverifikasi kembali!</p>
                                        @else
                                            <p class="mb-0 fw-medium text-danger">Pengajuan Belum Diverifikasi!</p>
                                        @endif
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
                                        <a href="javascript:void(0)" data-id_akses_ajukan="{{ $statusVerifikasi['must_akses'] }}" data-bs-toggle="modal" data-bs-target="#modal-ajukan" class="btn btn-success btn-sm d-flex align-items-center">
                                            <i class="bx bx-paper-plane"></i>&nbsp;Ajukan Pengajuan
                                        </a>
                                    @endif
                                    @if($statusVerifikasi['must_aprove'] == 'SUDAH DIREVISI')
                                        <a href="javascript:void(0)" data-id_akses_sudahrevisi="{{ $statusVerifikasi['must_akses'] }}" data-bs-toggle="modal" data-bs-target="#modal-sudahrevisi" class="btn btn-info btn-sm d-flex align-items-center">
                                            <i class="bx bx-paper-plane"></i>&nbsp;Sudah Direvisi
                                        </a>
                                    @endif
                                    @if($statusVerifikasi['must_aprove'] == 'VERIFIKASI')
                                        <a href="javascript:void(0)" data-id_pihakpenyetuju="{{ $statusVerifikasi['must_pihakpenyetuju'] }}" data-id_akses_setujui="{{ $statusVerifikasi['must_akses'] }}" data-bs-toggle="modal" data-bs-target="#modal-setujui" class="btn btn-success btn-sm d-flex align-items-center">
                                            <i class="bx bx-check-circle"></i>&nbsp;Setujui
                                        </a>
                                        &nbsp;&nbsp;
                                        <a href="javascript:void(0)" data-id_pihakpenyetuju="{{ $statusVerifikasi['must_pihakpenyetuju'] }}" data-id_akses_revisi="{{ $statusVerifikasi['must_akses'] }}" data-bs-toggle="modal" data-bs-target="#modal-revisi" class="btn btn-warning btn-sm d-flex align-items-center">
                                            <i class="bx bx-revision"></i>&nbsp;Revisi
                                        </a>
                                        &nbsp;&nbsp;
                                        <a href="javascript:void(0)" data-id_pihakpenyetuju="{{ $statusVerifikasi['must_pihakpenyetuju'] }}" data-id_akses_tolak="{{ $statusVerifikasi['must_akses'] }}" data-bs-toggle="modal" data-bs-target="#modal-tolak" class="btn btn-danger btn-sm d-flex align-items-center">
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
            <form action="{{ route('pengajuansurat.ajukan') }}" method="POST">
                @csrf
                <input type="hidden" name="id_pengajuan" value="{{ $id_pengajuan }}" >
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
                <input type="hidden" name="id_pengajuan" value="{{ $id_pengajuan }}" >
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
                <input type="hidden" name="id_pengajuan" value="{{ $id_pengajuan }}" >
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
            <form id="frmSetujuiPengajuan" action="{{ route('pengajuansurat.setujui') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_pengajuan" value="{{ $id_pengajuan }}" >
                <input type="hidden" name="id_akses" id="id_akses_setujui" >
                <input type="hidden" name="id_pihakpenyetuju" id="id_pihakpenyetuju_setujui" >
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
                <input type="hidden" name="id_pengajuan" value="{{ $id_pengajuan }}" >
                <input type="hidden" name="id_akses" id="id_akses_revisi" >
                <input type="hidden" name="id_pihakpenyetuju" id="id_pihakpenyetuju_revisi" >
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
                <input type="hidden" name="id_pengajuan" value="{{ $id_pengajuan }}" >
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
                <input type="hidden" name="id_pengajuan" value="{{ $id_pengajuan }}" >
                <input type="hidden" name="id_akses" id="id_akses_tolak" >
                <input type="hidden" name="id_pihakpenyetuju" id="id_pihakpenyetuju_tolak" >
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
@endsection
@section('page-script')
    <script>
        const isEdit = {{ $isEdit ? 'true' : 'false' }};
        const routeGetJenisSurat = "{{ route('pengajuansurat.getjenissurat') }}";
    </script>
    @vite('resources/views/script_view/pengajuan_surat/detail_pengajuan.js')
@endsection
