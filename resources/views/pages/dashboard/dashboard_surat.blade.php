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
    <div class="row pt-2 g-6 mb-6">
        <div class="col-md-12 col-xxl-4">
            <div class="card p-4 h-100">
                <div class="row">
                    <!-- Left side: rating average and total -->
                    <div class="col-md-4 d-flex flex-column align-items-center justify-content-center border-end">
                        <h2 class="text-primary fw-bold mb-1" style="font-size: 2.5rem;">{{ round($dataSurveyKepuasan->average_rating, 2) }} <span class="text-warning">★</span></h2>
                        <div class="text-muted mb-2">Total {{ $dataSurveyKepuasan->total_responden }} reviews</div>
                    </div>
                    @php
                        $total = $dataSurveyKepuasan->total_responden ?: 1;

                        $percent_1 = ($dataSurveyKepuasan->rating_1 / $total) * 100;
                        $percent_2 = ($dataSurveyKepuasan->rating_2 / $total) * 100;
                        $percent_3 = ($dataSurveyKepuasan->rating_3 / $total) * 100;
                        $percent_4 = ($dataSurveyKepuasan->rating_4 / $total) * 100;
                        $percent_5 = ($dataSurveyKepuasan->rating_5 / $total) * 100;
                    @endphp
                        <!-- Right side: rating breakdown -->
                    <div class="col-md-8 ps-md-4 small">
                        <div class="d-flex align-items-center mb-1">
                            <div style="width: 60px;">5 Star</div>
                            <div class="flex-grow-1 mx-3">
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percent_5 }}%;" aria-valuenow="{{ $percent_5 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div style="width: 30px;" class="text-end">{{ $dataSurveyKepuasan->rating_5 }} </div>
                        </div>

                        <div class="d-flex align-items-center mb-1">
                            <div style="width: 60px;">4 Star</div>
                            <div class="flex-grow-1 mx-3">
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percent_4 }}%;" aria-valuenow="{{ $percent_4 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div style="width: 30px;" class="text-end">{{ $dataSurveyKepuasan->rating_4 }}</div>
                        </div>

                        <div class="d-flex align-items-center mb-1">
                            <div style="width: 60px;">3 Star</div>
                            <div class="flex-grow-1 mx-3">
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percent_3 }}%;" aria-valuenow="{{ $percent_3 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div style="width: 30px;" class="text-end">{{ $dataSurveyKepuasan->rating_3 }}</div>
                        </div>

                        <div class="d-flex align-items-center mb-1">
                            <div style="width: 60px;">2 Star</div>
                            <div class="flex-grow-1 mx-3">
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percent_2 }}%;" aria-valuenow="{{ $percent_2 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div style="width: 30px;" class="text-end">{{ $dataSurveyKepuasan->rating_2 }}</div>
                        </div>

                        <div class="d-flex align-items-center">
                            <div style="width: 60px;">1 Star</div>
                            <div class="flex-grow-1 mx-3">
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percent_1 }}%;" aria-valuenow="{{ $percent_1 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div style="width: 30px;" class="text-end">{{ $dataSurveyKepuasan->rating_1 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-8">
            <div class="card h-100">
                <div class="card-widget-separator-wrapper">
                    <div class="card-body card-widget-separator">
                        <div class="row gy-4 gy-sm-1">
                            <div class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
                                    <div>
                                        <p class="mb-1">Total Pengajuan</p>
                                        <h4 class="mb-1" id="total_pengajuan">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </h4>
                                    </div>
                                    <span class="avatar me-sm-6"><span class="avatar-initial rounded w-px-44 h-px-44 bg-label-primary"><i class="bx bx-book-content bx-lg text-heading"></i></span></span>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none me-6">
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
                                    <div>
                                        <p class="mb-1">Total Disetujui</p>
                                        <h4 class="mb-1" id="total_disetujui">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </h4>
                                    </div>
                                    <span class="avatar p-2 me-lg-6"><span class="avatar-initial rounded w-px-44 h-px-44 bg-label-success"><i class="bx bx-check bx-lg text-heading"></i></span></span>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none">
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
                                    <div>
                                        <p class="mb-1">Total Onproses</p>
                                        <h4 class="mb-1" id="total_onproses">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </h4>
                                    </div>
                                    <span class="avatar p-2 me-sm-6"><span class="avatar-initial rounded w-px-44 h-px-44 bg-label-warning"><i class="bx bx-loader bx-lg text-heading"></i></span></span>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="mb-1">Total Ditolak</p>
                                        <h4 class="mb-1" id="total_ditolak">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </h4>
                                    </div>
                                    <span class="avatar p-2"><span class="avatar-initial rounded w-px-44 h-px-44 bg-label-danger"><i class="bx bx-x bx-lg text-heading"></i></span></span>
                                </div>
                            </div>
                        </div>
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
        const routeGetDataSurat = "{{ route('dashboard.suratgetdata') }}";
        const istilahPersuratan = "{{ $istilahPersuratan }}";
    </script>
    @vite('resources/views/script_view/dashboard_surat/script.js')
@endsection
