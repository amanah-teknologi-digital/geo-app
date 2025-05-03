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
                        <a href="#">Master Data</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('ruangan') }}">Ruangan</a>
                    </li>
                    <li class="breadcrumb-item active">Jadwal</li>
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
                    <h5 class="card-title mb-0"><i class="bx bx-calendar mb-1"></i>&nbsp;Jadwal {{ $dataRuangan->nama }}</h5>
                    <a href="{{ route('ruangan') }}" class="btn btn-sm btn-secondary btn-sm mb-0">
                        <i class="bx bx-arrow-back"></i>&nbsp;Kembali
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="card shadow-none app-calendar-wrapper">
                        <div class="row g-0">
                            <div class="col app-calendar-sidebar border-end" id="app-calendar-sidebar">
                                @if($isEdit)
                                    <div class="border-bottom p-6 my-sm-0 mb-4">
                                        <button class="btn btn-primary btn-toggle-sidebar w-100" data-bs-toggle="offcanvas" data-bs-target="#addEventSidebar" aria-controls="addEventSidebar">
                                            <i class="icon-base bx bx-plus icon-16px me-2"></i>
                                            <span class="align-middle">Tambah Jadwal</span>
                                        </button>
                                    </div>
                                @endif
                                <div class="px-6 pb-2 my-sm-0 p-4">
                                    <!-- Filter -->
                                    <div>
                                        <h5>Filter Jadwal</h5>
                                    </div>

                                    <div class="form-check form-check-secondary mb-5 ms-2">
                                        <input class="form-check-input select-all" type="checkbox" id="selectAll" data-value="all" checked="">
                                        <label class="form-check-label" for="selectAll">Tampilkan Semua</label>
                                    </div>

                                    <div class="app-calendar-events-filter text-heading">
                                        <div class="form-check form-check-success mb-5 ms-2">
                                            <input class="form-check-input input-filter" type="checkbox" id="select-jadwal" data-value="jadwal" checked="">
                                            <label class="form-check-label" for="select-jadwal">Jadwal Kuliah</label>
                                        </div>
                                        <div class="form-check form-check-primary mb-5 ms-2">
                                            <input class="form-check-input input-filter" type="checkbox" id="select-booking" data-value="booking" checked="">
                                            <label class="form-check-label" for="select-booking">Jadwal Booking</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col app-calendar-content">
                                <div class="card shadow-none border-0">
                                    <div class="card-body pb-0">
                                        <div id="calendar"></div>
                                    </div>
                                </div>
                                <div class="app-overlay"></div>
                            </div>
                        </div>
                    </div>
                    <ul class="fa-ul ml-auto float-end mt-5 p-4">
                        <li>
                            <small><em>Hanya ruangan berstatus <b>aktif</b> yang bisa dibooking!.</em></small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="offcanvas offcanvas-end event-sidebar" tabindex="-1" id="addEventSidebar" aria-labelledby="addEventSidebarLabel" >
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="addEventSidebarLabel">Tambah Jadwal</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body" data-select2-id="7">
            <form id="tambahJadwal" method="POST" action="{{ route('ruangan.dotambahjadwal') }}">
                @csrf
                <input type="hidden" name="idRuangan" value="{{ $idRuangan }}" required>
                <div class="mb-6">
                    <label class="form-label" for="keterangan">Keterangan Jadwal <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="keterangan" name="keterangan" autocomplete="off" autofocus placeholder="keterangan jadwal" required>
                </div>
                <div class="mb-6">
                    <label class="form-label" for="hari">Pilih Hari <span class="text-danger">*</span></label>
                    <select class="form-control" name="hari" id="hari" required>
                        <option value="" selected disabled>-- pilih hari --</option>
                        @foreach ($hari as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-6">
                    <label class="form-label" for="tgl_jadwal">Pilih Tanggal <span class="text-danger">*</span></label>
                    <input type="text" id="tgl_jadwal" class="form-control" name="tgl_jadwal" required placeholder="pilih tanggal mulai - selesai" autocomplete="off">
                </div>
                <div class="mb-6">
                    <label class="form-label" for="jam_jadwal">Pilih Waktu <span class="text-danger">*</span></label>
                    <div class="d-inline-flex gap-2">
                        <input type="text" id="jam_mulai" class="form-control jam_jadwal" name="jam_mulai" placeholder="pilih jam mulai" autocomplete="off">
                        <input type="text" id="jam_selesai" class="form-control jam_jadwal" name="jam_selesai" placeholder="pilih jam selesai" autocomplete="off">
                    </div>
                    <div class="error-container" id="error-jammulai"></div>
                    <div class="error-container" id="error-jamselesai"></div>
                </div>
                <div class="d-flex justify-content-sm-between justify-content-start mt-6 gap-2">
                    <div class="d-flex">
                        <button type="submit" id="addEventBtn" class="btn btn-primary me-4 btn-add-event"><span class="bx bx-save"></span>&nbsp;Tambah</button>
                        <button type="reset" class="btn btn-label-secondary btn-cancel me-sm-0 me-1" data-bs-dismiss="offcanvas">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="offcanvas offcanvas-end event-sidebar" tabindex="-1" id="addEventSidebarUpdate" aria-labelledby="addEventSidebarUpdateLabel" >
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="addEventSidebarUpdateLabel">Update Jadwal</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body" data-select2-id="7">
            <form id="updateJadwal" method="POST" action="{{ route('ruangan.doupdatejadwal') }}">
                @csrf
                <input type="hidden" name="idRuangan" value="{{ $idRuangan }}" required>
                <input type="hidden" name="idJadwal" id="idJadwal" required>
                <div class="mb-6">
                    <label class="form-label" for="keterangan_update">Keterangan Jadwal <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="keterangan_update" name="keterangan" autocomplete="off" autofocus placeholder="keterangan jadwal" required>
                </div>
                <div class="mb-6">
                    <label class="form-label" for="hari_update">Pilih Hari <span class="text-danger">*</span></label>
                    <select class="form-control" name="hari" id="hari_update" required>
                        <option value="" selected disabled>-- pilih hari --</option>
                        @foreach ($hari as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-6">
                    <label class="form-label" for="tgl_jadwal_update">Pilih Tanggal <span class="text-danger">*</span></label>
                    <input type="text" id="tgl_jadwal_update" class="form-control" name="tgl_jadwal" required placeholder="pilih tanggal mulai - selesai" autocomplete="off">
                </div>
                <div class="mb-6">
                    <label class="form-label" for="jam_jadwal_update">Pilih Waktu <span class="text-danger">*</span></label>
                    <div class="d-inline-flex gap-2">
                        <input type="text" id="jam_mulai_update" class="form-control jam_jadwal_update" name="jam_mulai" placeholder="pilih jam mulai" autocomplete="off">
                        <input type="text" id="jam_selesai_update" class="form-control jam_jadwal_update" name="jam_selesai" placeholder="pilih jam selesai" autocomplete="off">
                    </div>
                    <div class="error-container" id="error-jammulai_update"></div>
                    <div class="error-container" id="error-jamselesai_update"></div>
                </div>
                <div class="d-flex justify-content-sm-between justify-content-start mt-6 gap-2">
                    <div class="d-flex">
                        <button type="submit" id="addEventBtnUpdate" class="btn btn-warning text-black me-4 d-none"><span class="bx bx-save"></span>&nbsp;Update</button>
                        <a href="javascript:void(0)" class="btn btn-danger d-none" id="tombolHapus" data-bs-toggle="modal" data-bs-target="#modal-hapus" data-id="" ><span class="bx bx-trash"></span>&nbsp;Hapus</a>
                    </div>
                    <button type="reset" class="btn btn-label-secondary btn-cancel me-sm-0 me-1" data-bs-dismiss="offcanvas">Batal</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="modal-hapus" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <form action="{{ route('ruangan.dohapusjadwal') }}" method="POST">
                @csrf
                <input type="hidden" name="idRuangan" value="{{ $idRuangan }}" required>
                <input type="hidden" name="idJadwal" id="idHapus" required>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel2">Hapus Jadwal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah yakin menghapus jadwal ini?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('page-script')
    <script>
        const urlGetData = '{{ route('ruangan.getdatajadwal') }}';
        const isEdit = {{ $isEdit ? 'true' : 'false' }};
        const idRuangan = '{{ $idRuangan }}';
    </script>
    @vite([
        'resources/views/script_view/ruangan/jadwal_ruangan.js'
    ])
@endsection
