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
                        <a href="{{ route('jenissurat') }}">Jenis Surat</a>
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
                        <h5 class="card-title mb-0"><i class="bx bx-plus mb-1"></i>&nbsp;Tambah Jenis Surat</h5>
                        <a href="{{ route('jenissurat') }}" class="btn btn-sm btn-secondary btn-sm">
                            <i class="bx bx-arrow-back"></i>&nbsp;Kembali
                        </a>
                    </div>
                    <div class="card-body pt-4">
                        <form id="formJenisSurat" method="POST" action="{{ route('jenissurat.dotambah') }}">
                            @csrf
                            <div class="row g-6">
                                <div>
                                    <label for="nama_jenis" class="form-label">Nama Jenis Surat <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama_jenis" name="nama_jenis" placeholder="Nama jenis surat" required autocomplete="off" autofocus>
                                </div>
                                <div>
                                    <label for="isi_template" class="form-label">Template Surat <span class="text-danger">*</span></label>
                                    <div id="editor_template" style="height: 700px;"></div>
                                    <input type="hidden" name="editor_quil" id="editor_quil">
                                    <div class="error-container" id="error-quil"></div>
                                </div>
                            </div>
                            <div class="mt-6">
                                <button type="submit" class="btn btn-primary me-3"><i class="bx bx-save"></i>&nbsp;Tambah Jenis Surat</button>
                            </div>
                        </form>
                    </div>
            </div>
        </div>
    </div>
@endsection
@section('page-script')
    @vite('resources/views/script_view/jenis_surat/tambah_jenissurat.js')
@endsection
