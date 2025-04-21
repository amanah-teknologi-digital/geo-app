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
                        <a href="#">Master Data</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('ruangan') }}">Ruangan</a>
                    </li>
                    <li class="breadcrumb-item active">Detail</li>
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
                <div class="card-header d-flex justify-content-between align-items-center pb-4 border-bottom">
                    <h5 class="card-title mb-0"><i class="bx bx-plus mb-1"></i>&nbsp;Detail Ruangan</h5>
                    <a href="{{ route('ruangan') }}" class="btn btn-sm btn-secondary btn-sm mb-0">
                        <i class="bx bx-arrow-back"></i>&nbsp;Kembali
                    </a>
                </div>
                <div class="card-body pt-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-6 gap-2">
                        <div class="me-1">
                            <h5 class="mb-0">UI/UX Basic Fundamentals</h5>
                            <p class="mb-0">Prof. <span class="fw-medium text-heading"> Devonne Wallbridge </span></p>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-label-danger">UI/UX</span>
                            <i class="icon-base bx bx-share-alt icon-lg mx-4"></i>
                            <i class="icon-base bx bx-bookmarks icon-lg"></i>
                        </div>
                    </div>
                    <div class="card academy-content shadow-none border">
                        @php
                            $file = $dataRuangan->gambar_file;
                            $filePath = $dataRuangan->gambar->location;
                            $imageUrl = Storage::disk('public')->exists($filePath)
                                ? route('file.getpublicfile', $file)
                                : asset('assets/img/no_image.jpg');
                        @endphp
                        <div class="p-2">
                            <img class="img-fluid" style="aspect-ratio: 4 / 3;border-radius: 8px;object-fit: cover;width: 100%;" src="{{ $imageUrl }}" alt="{{ $dataRuangan->nama }}">
                        </div>
                        <div class="card-body pt-4">
                            <h5>About this course</h5>
                            <p class="mb-0">Learn web design in 1 hour with 25+ simple-to-use rules and guidelines — tons of amazing web design resources included!</p>
                            <hr class="my-6">
                            <h5>By the numbers</h5>
                            <div class="d-flex flex-wrap row-gap-2">
                                <div class="me-12">
                                    <p class="text-nowrap mb-2"><i class="icon-base bx bx-check me-2 align-bottom"></i>Skill level: All Levels</p>
                                    <p class="text-nowrap mb-2"><i class="icon-base bx bx-group me-2 align-top"></i>Students: 38,815</p>
                                    <p class="text-nowrap mb-2"><i class="icon-base bx bx-globe me-2 align-bottom"></i>Languages: English</p>
                                    <p class="text-nowrap mb-0"><i class="icon-base bx bx-file me-2 align-bottom"></i>Captions: Yes</p>
                                </div>
                                <div>
                                    <p class="text-nowrap mb-2"><i class="icon-base bx bx-video me-2 align-top ms-50"></i>Lectures: 19</p>
                                    <p class="text-nowrap mb-0"><i class="icon-base bx bx-time-five me-2 align-top"></i>Video: 1.5 total hours</p>
                                </div>
                            </div>
                            <hr class="my-6">
                            <h5>Description</h5>
                            <p class="mb-6">The material of this course is also covered in my other course about web design and development with HTML5 &amp; CSS3. Scroll to the bottom of this page to check out that course, too! If you're already taking my other course, you already have all it takes to start designing beautiful websites today!</p>
                            <p class="mb-6">"Best web design course: If you're interested in web design, but want more than just a "how to use WordPress" course,I highly recommend this one." — Florian Giusti</p>
                            <p>"Very helpful to us left-brained people: I am familiar with HTML, CSS, JQuery, and Twitter Bootstrap, but I needed instruction in web design. This course gave me practical, impactful techniques for making websites more beautiful and engaging." — Susan Darlene Cain</p>
                            <hr class="my-6">
                            <h5>Instructor</h5>
                            <div class="d-flex justify-content-start align-items-center user-name">
                                <div class="avatar-wrapper">
                                    <div class="avatar me-4"><img src="../../assets/img/avatars/11.png" alt="Avatar" class="rounded-circle"></div>
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1">Devonne Wallbridge</h6>
                                    <small>Web Developer, Designer, and Teacher</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-script')
    @vite('resources/views/script_view/ruangan/detail_ruangan.js')
@endsection
