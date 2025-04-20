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
                <div class="card-header d-flex flex-wrap justify-content-between gap-4">
                    <div class="card-title mb-0 me-1">
                        <h5 class="mb-0">My Courses</h5>
                        <p class="mb-0">Total 6 course you have purchased</p>
                    </div>
                    <div class="d-flex justify-content-md-end align-items-sm-center align-items-start column-gap-6 flex-sm-row flex-column row-gap-4">
                        <select class="form-select">
                            <option value="">All Courses</option>
                            <option value="ui/ux">UI/UX</option>
                            <option value="seo">SEO</option>
                            <option value="web">Web</option>
                            <option value="music">Music</option>
                            <option value="painting">Painting</option>
                        </select>

                        <div class="form-check form-switch my-2 ms-2">
                            <input type="checkbox" class="form-check-input" id="CourseSwitch">
                            <label class="form-check-label text-nowrap mb-0" for="CourseSwitch">Hide completed</label>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row gy-6 mb-6">
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
                        <div class="col-sm-6 col-lg-4">
                            <div class="card p-2 h-100 shadow-none border">
                                <div class="rounded-2 text-center mb-4">
                                    <a href="app-academy-course-details.html"><img class="img-fluid" src="../../assets/img/pages/app-academy-tutor-2.png" alt="tutor image 2"></a>
                                </div>
                                <div class="card-body p-4 pt-2">
                                    <div class="d-flex justify-content-between align-items-center mb-4 pe-xl-4 pe-xxl-0">
                                        <span class="badge bg-label-danger">UI/UX</span>
                                        <p class="d-flex align-items-center justify-content-center fw-medium gap-1 mb-0">
                                            4.2 <span class="text-warning"><i class="icon-base bx bxs-star me-1 mb-1_5"></i></span><span class="fw-normal"> (424)</span>
                                        </p>
                                    </div>
                                    <a class="h5" href="app-academy-course-details.html">Figma &amp; More</a>
                                    <p class="mt-1">Introductory course for design and framework basics in web development.</p>
                                    <p class="d-flex align-items-center mb-1"><i class="icon-base bx bx-time-five me-1"></i>16 hours</p>
                                    <div class="progress mb-4" style="height: 8px">
                                        <div class="progress-bar w-25" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="d-flex flex-column flex-md-row gap-4 text-nowrap flex-wrap flex-md-nowrap flex-lg-wrap flex-xxl-nowrap">
                                        <a class="w-100 btn btn-label-secondary d-flex align-items-center" href="app-academy-course-details.html"> <i class="icon-base bx bx-rotate-right icon-sm align-middle me-2"></i><span>Start Over</span> </a>
                                        <a class="w-100 btn btn-label-primary d-flex align-items-center" href="app-academy-course-details.html"> <span class="me-2">Continue</span><i class="icon-base bx bx-chevron-right icon-sm lh-1 scaleX-n1-rtl"></i> </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="card p-2 h-100 shadow-none border">
                                <div class="rounded-2 text-center mb-4">
                                    <a href="app-academy-course-details.html"><img class="img-fluid" src="../../assets/img/pages/app-academy-tutor-3.png" alt="tutor image 3"></a>
                                </div>
                                <div class="card-body p-4 pt-2">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <span class="badge bg-label-success">SEO</span>
                                        <p class="d-flex align-items-center justify-content-center fw-medium gap-1 mb-0">
                                            5 <span class="text-warning"><i class="icon-base bx bxs-star me-1 mb-1_5"></i></span><span class="fw-normal"> (12)</span>
                                        </p>
                                    </div>
                                    <a class="h5" href="app-academy-course-details.html">Keyword Research</a>
                                    <p class="mt-1">Keyword suggestion tool provides comprehensive details &amp; keyword suggestions.</p>
                                    <p class="d-flex align-items-center mb-1"><i class="icon-base bx bx-time-five me-1"></i>7 hours</p>
                                    <div class="progress mb-4" style="height: 8px">
                                        <div class="progress-bar w-50" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="d-flex flex-column flex-md-row gap-4 text-nowrap flex-wrap flex-md-nowrap flex-lg-wrap flex-xxl-nowrap">
                                        <a class="w-100 btn btn-label-secondary d-flex align-items-center" href="app-academy-course-details.html"> <i class="icon-base bx bx-rotate-right icon-sm align-middle me-2"></i><span>Start Over</span> </a>
                                        <a class="w-100 btn btn-label-primary d-flex align-items-center" href="app-academy-course-details.html"> <span class="me-2">Continue</span><i class="icon-base bx bx-chevron-right icon-sm lh-1 scaleX-n1-rtl"></i> </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="card p-2 h-100 shadow-none border">
                                <div class="rounded-2 text-center mb-4">
                                    <a href="app-academy-course-details.html"><img class="img-fluid" src="../../assets/img/pages/app-academy-tutor-4.png" alt="tutor image 4"></a>
                                </div>
                                <div class="card-body p-4 pt-2">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <span class="badge bg-label-info">Music</span>
                                        <p class="d-flex align-items-center justify-content-center gap-1 mb-0">
                                            3.8 <span class="text-warning"><i class="icon-base bx bxs-star me-1 mb-1_5"></i></span><span class="fw-normal"> (634)</span>
                                        </p>
                                    </div>
                                    <a class="h5" href="app-academy-course-details.html">Basics to Advanced</a>
                                    <p class="mt-1">20 more lessons like this about music production, writing, mixing, mastering</p>
                                    <p class="d-flex align-items-center mb-1"><i class="icon-base bx bx-time-five me-1"></i>30 minutes</p>
                                    <div class="progress mb-4" style="height: 8px">
                                        <div class="progress-bar w-75" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="d-flex flex-column flex-md-row gap-4 text-nowrap flex-wrap flex-md-nowrap flex-lg-wrap flex-xxl-nowrap">
                                        <a class="w-100 btn btn-label-secondary d-flex align-items-center" href="app-academy-course-details.html"> <i class="icon-base bx bx-rotate-right icon-sm align-middle me-2"></i><span>Start Over</span> </a>
                                        <a class="w-100 btn btn-label-primary d-flex align-items-center" href="app-academy-course-details.html"> <span class="me-2">Continue</span><i class="icon-base bx bx-chevron-right icon-sm lh-1 scaleX-n1-rtl"></i> </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="card p-2 h-100 shadow-none border">
                                <div class="rounded-2 text-center mb-4">
                                    <a href="app-academy-course-details.html"><img class="img-fluid" src="../../assets/img/pages/app-academy-tutor-5.png" alt="tutor image 5"></a>
                                </div>
                                <div class="card-body p-4 pt-2">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <span class="badge bg-label-warning">Painting</span>
                                        <p class="d-flex align-items-center justify-content-center gap-1 mb-0">
                                            4.7 <span class="text-warning"><i class="icon-base bx bxs-star me-1 mb-1_5"></i></span><span class="fw-normal"> (34)</span>
                                        </p>
                                    </div>
                                    <a class="h5" href="app-academy-course-details.html">Art &amp; Drawing</a>
                                    <p class="mt-1">Easy-to-follow video &amp; guides show you how to draw animals, people &amp; more.</p>
                                    <p class="d-flex align-items-center text-success mb-1"><i class="icon-base bx bx-check me-1"></i>Completed</p>
                                    <div class="progress mb-4" style="height: 8px">
                                        <div class="progress-bar w-100" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <a class="w-100 btn btn-label-primary" href="app-academy-course-details.html"><i class="icon-base bx bx-rotate-right icon-sm me-1_5"></i>Start Over</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="card p-2 h-100 shadow-none border">
                                <div class="rounded-2 text-center mb-4">
                                    <a href="app-academy-course-details.html"><img class="img-fluid" src="../../assets/img/pages/app-academy-tutor-6.png" alt="tutor image 6"></a>
                                </div>
                                <div class="card-body p-4 pt-2">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <span class="badge bg-label-danger">UI/UX</span>
                                        <p class="d-flex align-items-center justify-content-center gap-1 mb-0">
                                            3.6 <span class="text-warning"><i class="icon-base bx bxs-star me-1 mb-1_5"></i></span><span class="fw-normal"> (2.5k)</span>
                                        </p>
                                    </div>
                                    <a class="h5" href="app-academy-course-details.html">Basics Fundamentals</a>
                                    <p class="mt-1">This guide will help you develop a systematic approach user interface.</p>
                                    <p class="d-flex align-items-center mb-1"><i class="icon-base bx bx-time-five me-1"></i>16 hours</p>
                                    <div class="progress mb-4" style="height: 8px">
                                        <div class="progress-bar w-25" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="d-flex flex-column flex-md-row gap-4 text-nowrap flex-wrap flex-md-nowrap flex-lg-wrap flex-xxl-nowrap">
                                        <a class="w-100 btn btn-label-secondary d-flex align-items-center" href="app-academy-course-details.html"> <i class="icon-base bx bx-rotate-right icon-sm align-middle me-2"></i><span>Start Over</span> </a>
                                        <a class="w-100 btn btn-label-primary d-flex align-items-center" href="app-academy-course-details.html"> <span class="me-2">Continue</span><i class="icon-base bx bx-chevron-right icon-sm lh-1 scaleX-n1-rtl"></i> </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <nav aria-label="Page navigation" class="d-flex align-items-center justify-content-center">
                        <ul class="pagination mb-0 pagination-rounded">
                            <li class="page-item first disabled">
                                <a class="page-link" href="javascript:void(0);"><i class="icon-base bx bx-chevrons-left icon-sm scaleX-n1-rtl"></i></a>
                            </li>
                            <li class="page-item prev disabled">
                                <a class="page-link" href="javascript:void(0);"><i class="icon-base bx bx-chevron-left icon-sm scaleX-n1-rtl"></i></a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">2</a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="javascript:void(0);">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">4</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">5</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0);">6</a>
                            </li>
                            <li class="page-item next">
                                <a class="page-link" href="javascript:void(0);"><i class="icon-base bx bx-chevron-right icon-sm scaleX-n1-rtl"></i></a>
                            </li>
                            <li class="page-item last">
                                <a class="page-link" href="javascript:void(0);"><i class="icon-base bx bx-chevrons-right icon-sm scaleX-n1-rtl"></i></a>
                            </li>
                        </ul>
                    </nav>
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
