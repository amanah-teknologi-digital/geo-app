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
            <div class="card" style="margin-bottom: 5.5rem !important;">
                <div class="card-header d-flex justify-content-between align-items-center pb-4 border-bottom">
                    <h5 class="card-title mb-0"><i class="bx bx-envelope"></i>&nbsp;Data Persuratan</h5>
                    <h5 class="card-title mb-0"><i class="bx bx-stats"></i>&nbsp;Status Pengajuan: <span class="fst-italic" style="color: {{ $dataPengajuan->statuspengajuan->html_color }}">{{ $dataPengajuan->statuspengajuan->nama }}</span></h5>
                </div>
                <div class="card-body pt-4">
                    <form id="formPengajuan" method="POST" action="{{ route('pengajuansurat.doupdate') }}">
                        @csrf
                        <input type="hidden" name="id_pengajuan" value="{{ $id_pengajuan }}" required>
                        <div class="row g-6">
                            <div>
                                <label for="jenis_surat" class="form-label">Jenis Surat <span
                                        class="text-danger">*</span></label>
                                <select name="jenis_surat" id="jenis_surat" class="form-control" required {{ $isEdit? '':'disabled' }} >
                                    <option value="" selected disabled>-- Pilih Jenis Surat --</option>
                                    @foreach($dataJenisSurat as $row)
                                        <option value="{{ $row->id_jenissurat }}" {{ ($dataPengajuan->id_jenissurat == $row->id_jenissurat) ? 'selected':'' }}>{{ $row->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="isi_surat" class="form-label">Form Isi Surat <span
                                        class="text-danger">*</span></label>
                                <div id="editor_surat" style="height: 500px;">{!! $dataPengajuan->data_form !!}</div>
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
                    <ul class="fa-ul ml-auto float-end mt-5">
                        <li>
                            <small><em>Dengan <b>ajukan pengajuan</b>, pengajuan akan <b>diverifikasi</b> oleh admin. Pengajuan yang berstatus <b>Draft</b> masih bisa <b>dihapus/dibatalkan</b></em></small>
                        </li>
                        <li>
                            <small><em>Selama berstatus <b>Diajukan</b>, maka tidak diperbolehkan <b>mengupdate</b> pengajuan kecuali ada <b>revisi dari admin</b>.</em></small>
                        </li>
                        <li>
                            <small><em>Jika ada <b>revisi dari admin</b>, maka update data <b>pengajuan</b> atau <b>biodata</b> sesuai dengan <b>arahan revisi</b> pada histori persetujuan.</em></small>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="position-fixed bottom-0 mb-10 start-50 translate-middle-x px-3 pb-3" style="z-index: 1050; width: 100%;">
                <div class="fixed-verifikasi-card">
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
                                        <p class="mb-0 fw-medium text-danger">Pengajuan sudah direvisi dan belum Diverifikasi kembali!</p>
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
                                    <a href="javascript:void(0)" onclick="goSudahRevisi({{ $statusVerifikasi['must_akses'] }})" class="btn btn-info btn-sm d-flex align-items-center">
                                        <i class="bx bx-paper-plane"></i>&nbsp;Sudah Direvisi
                                    </a>
                                @endif
                                @if($statusVerifikasi['must_aprove'] == 'VERIFIKASI')
                                    <a href="javascript:void(0)" data-id_akses_setujui="{{ $statusVerifikasi['must_akses'] }}" data-bs-toggle="modal" data-bs-target="#modal-setujui" class="btn btn-success btn-sm d-flex align-items-center">
                                        <i class="bx bx-check-circle"></i>&nbsp;Setujui
                                    </a>
                                    &nbsp;&nbsp;
                                    <a href="javascript:void(0)" onclick="goRevisi({{ $statusVerifikasi['must_akses'] }})" class="btn btn-warning btn-sm d-flex align-items-center">
                                        <i class="bx bx-revision"></i>&nbsp;Revisi
                                    </a>
                                    &nbsp;&nbsp;
                                    <a href="javascript:void(0)" onclick="goTolak({{ $statusVerifikasi['must_akses'] }})" class="btn btn-danger btn-sm d-flex align-items-center">
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
    <div class="modal fade" id="modal-setujui" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <form action="{{ route('pengajuansurat.setujui') }}" method="POST">
                @csrf
                <input type="hidden" name="id_pengajuan" value="{{ $id_pengajuan }}" >
                <input type="hidden" name="id_akses" id="id_akses_setujui" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel2">Setujui Pengajuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah yakin menyetujui pengajuan ini?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Iya</button>
                    </div>
                </div>
            </form>
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
