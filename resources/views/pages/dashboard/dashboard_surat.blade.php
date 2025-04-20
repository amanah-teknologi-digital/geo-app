@extends('layouts.contentNavbarLayout')

@section('title', $title.' • '.config('variables.templateName'))

@section('page-style')
    @vite('resources/assets/css/custom.scss')
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center">
            <label for="tahun" class="me-2 mb-0">Tahun:</label>
            <select name="tahun" id="tahun" class="form-select w-auto">
                <option value="2025">2025</option>
                <option value="2024">2024</option>
            </select>
        </div>
    </div>
    <div class="row mb-6 pt-2 g-6">
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="content-left">
                            <h5 class="mb-1" id="total_pengajuan">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </h5>
                            <small>Total Pengajuan</small>
                        </div>
                        <span class="badge bg-label-primary rounded-circle p-2"><i class="bx bx-book-content bx-lg"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="content-left">
                            <h5 class="mb-1" id="total_disetujui">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </h5>
                            <small>Total Disetujui</small>
                        </div>
                        <span class="badge bg-label-success rounded-circle p-2"><i class="bx bx-check bx-lg"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="content-left">
                            <h5 class="mb-1" id="total_onproses">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </h5>
                            <small>Total Onproses</small>
                        </div>
                        <span class="badge bg-label-warning rounded-circle p-2"><i class="bx bx-loader bx-lg"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="content-left">
                            <h5 class="mb-1" id="total_ditolak">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </h5>
                            <small>Total Ditolak</small>
                        </div>
                        <span class="badge bg-label-danger rounded-circle p-2"><i class="bx bx-x bx-lg"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xxl-12 mb-6">
            <div class="card">
                <div class="row row-bordered g-0">
                    <div class="col-md-12">
                        <div class="card-body" style="position: relative;">
                            <div id="chart"></div>
                            <br>
                            <p style="font-size: 12px; color: #666; text-align: center; margin-bottom: 10px;">
                                Geser ke kiri atau zoom untuk melihat data hari lainnya.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Total Income -->
        </div>
    </div>
@endsection
@section('page-script')
    <script>
        let routeGetDataSurat = "{{ route('dashboard.suratgetdata') }}";
        let istilahPersuratan = "{{ $istilahPersuratan }}";
    </script>
    @vite('resources/views/script_view/dashboard_surat/script.js')
@endsection
