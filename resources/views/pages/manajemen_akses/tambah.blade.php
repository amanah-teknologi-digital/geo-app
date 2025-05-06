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
                        <a href="{{ route('pengumuman') }}">Pengumuman</a>
                    </li>
                    <li class="breadcrumb-item active">Tambah</li>
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
                        <h5 class="card-title mb-0"><i class="bx bx-plus mb-1"></i>&nbsp;Tambah Pengumuman</h5>
                        <a href="{{ route('pengumuman') }}" class="btn btn-sm btn-secondary btn-sm">
                            <i class="bx bx-arrow-back"></i>&nbsp;Kembali
                        </a>
                    </div>
                    <div class="card-body pt-4">
                        <form id="formPengumuman" method="POST" action="{{ route('pengumuman.dotambah') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-6">
                                <div>
                                    <label for="judul" class="form-label">Judul Pengumuman <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="judul" name="judul" placeholder="judul" required autocomplete="off" autofocus>
                                </div>
                                <div>
                                    <label for="isi_pengumuman" class="form-label">Isi Pengumuman <span class="text-danger">*</span></label>
                                    <div id="editor-loading" class="text-center">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                    <textarea id="editor_pengumuman" name="editor_pengumuman" style="height: 500px;"></textarea>
                                    <div class="error-container" id="error-quil"></div>
                                </div>
                                <div>
                                    <label for="gambar_header" class="form-label">Gambar Header <span class="text-danger">*</span><span class="text-muted"><i><b>(File gambar max 5 mb)</b></i></span></label>
                                    <input type="file" class="form-control" id="gambar_header" name="gambar_header" accept="image/*">
                                </div>
                            </div>
                            <div class="mt-6">
                                <button type="submit" class="btn btn-primary me-3">Tambah</button>
                            </div>
                        </form>
                    </div>
            </div>
        </div>
    </div>
@endsection
@section('page-script')
    @vite('resources/views/script_view/tambah_pengumuman.js')
@endsection
