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
                                <div class="border-bottom p-6 my-sm-0 mb-4">
                                    <button class="btn btn-primary btn-toggle-sidebar w-100" data-bs-toggle="offcanvas" data-bs-target="#addEventSidebar" aria-controls="addEventSidebar">
                                        <i class="icon-base bx bx-plus icon-16px me-2"></i>
                                        <span class="align-middle">Tambah Jadwal</span>
                                    </button>
                                </div>
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
            <form class="event-form pt-0 fv-plugins-bootstrap5 fv-plugins-framework" id="eventForm" onsubmit="return false" novalidate="novalidate" data-select2-id="eventForm">
                <div class="mb-6 form-control-validation fv-plugins-icon-container">
                    <label class="form-label" for="eventTitle">Keterangan</label>
                    <input type="text" class="form-control" id="eventTitle" name="eventTitle" placeholder="Event Title">
                </div>
                <div class="mb-6 form-control-validation fv-plugins-icon-container fv-plugins-bootstrap5-row-valid">
                    <label class="form-label" for="eventStartDate">Start Date</label>
                    <div class="flatpickr-wrapper">
                        <input type="text" class="form-control flatpickr-input active" id="eventStartDate" name="eventStartDate" placeholder="Start Date" readonly="readonly">
                    </div>
                </div>
                <div class="d-flex justify-content-sm-between justify-content-start mt-6 gap-2">
                    <div class="d-flex">
                        <button type="submit" id="addEventBtn" class="btn btn-primary me-4 btn-add-event">Add</button>
                        <button type="reset" class="btn btn-label-secondary btn-cancel me-sm-0 me-1" data-bs-dismiss="offcanvas">Cancel</button>
                    </div>
                    <button class="btn btn-label-danger btn-delete-event d-none">Delete</button>
                </div>
                <input type="hidden"></form>
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
                            <td style="width: 25%">Nama Jadwal</td>
                            <td style="width: 1%">:</td>
                            <td style="width: 74%">&nbsp;<span class="fw-bold" id="eventModalTitle"></span></td>
                        </tr>
                        <tr>
                            <td style="width: 25%">Waktu Mulai</td>
                            <td style="width: 1%">:</td>
                            <td style="width: 74%">&nbsp;<span class="text-muted fw-bold fst-italic" id="eventModalStart"></span></td>
                        </tr>
                        <tr>
                            <td style="width: 25%">Waktu Selesai</td>
                            <td style="width: 1%">:</td>
                            <td style="width: 74%">&nbsp;<span class="text-muted fw-bold fst-italic" id="eventModalEnd"></span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-script')
    <script>
        let urlGetData = '{{ route('ruangan.getdatajadwal') }}';
    </script>
    @vite([
        'resources/views/script_view/ruangan/jadwal_ruangan.js'
    ])
@endsection
