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
                <ol class="breadcrumb flex">
                    <li class="breadcrumb-item">
                        <a href="#">Master Data</a>
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
                <div class="card-body">
                    <div class="mb-5 pb-4 border-bottom d-flex justify-content-between align-items-center">
                        <div class="head-label text-center">
                            <span class="card-header p-0"><i class="tf-icons bx bx-book-content"></i>&nbsp;List Ruangan</span>
                        </div>
                        <div class="dt-action-buttons text-end">
                            <div class="dt-buttons btn-group flex-wrap">
                                <div class="btn-group">
                                    <a href="{{ route('ruangan.tambah') }}" class="btn btn-secondary create-new btn-sm btn-primary" ><span><i class="icon-base bx bx-plus me-1"></i> <span class="d-none d-lg-inline-block">Tambah Ruangan</span></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-6 mb-6">
                        @if($dataRuangan->isNotEmpty())
                            @foreach($dataRuangan as $ruang)
                                <div class="col-sm-6 col-lg-4">
                                    <div class="card p-2 h-100 shadow-none border">
                                        <div class="rounded-2 text-center mb-4">
                                            <a href="app-academy-course-details.html"><img class="img-fluid" src="../../assets/img/pages/app-academy-tutor-1.png" alt="tutor image 1"></a>
                                        </div>
                                        <div class="card-body p-4 pt-2">
                                            <div class="d-flex justify-content-between align-items-center mb-4">
                                                <span class="badge bg-label-primary">Web</span>
                                                <p class="d-flex align-items-center justify-content-center fw-medium gap-1 mb-0">
                                                    4.4 <span class="text-warning"><i class="icon-base bx bxs-star me-1 mb-1_5"></i></span><span class="fw-normal">(1.23k)</span>
                                                </p>
                                            </div>
                                            <a href="app-academy-course-details.html" class="h5">Basics of Angular</a>
                                            <p class="mt-1">Introductory course for Angular and framework basics in web development.</p>
                                            <p class="d-flex align-items-center mb-1"><i class="icon-base bx bx-time-five me-1"></i>30 minutes</p>
                                            <div class="progress mb-4" style="height: 8px">
                                                <div class="progress-bar w-75" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <div class="d-flex flex-column flex-md-row gap-4 text-nowrap flex-wrap flex-md-nowrap flex-lg-wrap flex-xxl-nowrap">
                                                <a class="w-100 btn btn-label-secondary d-flex align-items-center" href="app-academy-course-details.html"> <i class="icon-base bx bx-rotate-right icon-sm align-middle scaleX-n1-rtl me-2"></i><span>Start Over</span> </a>
                                                <a class="w-100 btn btn-label-primary d-flex align-items-center" href="app-academy-course-details.html"> <span class="me-2">Continue</span><i class="icon-base bx bx-chevron-right icon-sm lh-1 scaleX-n1-rtl"></i> </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center">
                                <p class="text-muted">Data Ruangan Kosong!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
{{--@section('page-script')--}}
{{--    <script>--}}
{{--        let title = "{{ $title }}";--}}
{{--        let routeName = "{{ route('jenissurat.getdata') }}"; // Ensure route name is valid--}}
{{--        let routeTambah = "{{ route('jenissurat.tambah') }}"--}}
{{--    </script>--}}
{{--    @vite('resources/views/script_view/ruangan/list_ruangan.js')--}}
{{--@endsection--}}
