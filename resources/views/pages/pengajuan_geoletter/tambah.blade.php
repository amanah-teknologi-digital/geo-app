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
                        <a href="{{ route('pengumuman') }}">Pengajuan Geo Letter</a>
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
                        <h5 class="card-title mb-0"><i class="bx bx-plus"></i>&nbsp;Tambah Pengajuan</h5>
                        <a href="{{ route('pengajuangeoletter') }}" class="btn btn-sm btn-secondary btn-sm">
                            <i class="bx bx-arrow-back"></i>&nbsp;Kembali
                        </a>
                    </div>
                    <div class="card-body pt-4">
                        <form id="formPengajuan" method="POST" action="{{ route('pengajuangeoletter.dotambah') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-6">
                                <div>
                                    <label for="jenis_surat" class="form-label">Jenis Surat <span class="text-danger">*</span></label>
                                    <select name="jenis_surat" id="jenis_surat" class="form-control" required autofocus>
                                        <option value="" selected disabled>-- Pilih Jenis Surat --</option>
                                        @foreach($data_jenissurat as $row)
                                            <option value="{{ $row->id_jenissurat }}">{{ $row->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="isi_surat" class="form-label">Form Isi Surat <span class="text-danger">*</span></label>
                                    <div id="editor_surat" style="height: 250px;"></div>
                                    <input type="hidden" name="editor_quil" id="editor_quil">
                                    <div class="error-container" id="error-quil"></div>
                                </div>
                                <div>
                                    <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
                                    <textarea name="keterangan" id="keterangan" class="form-control" cols="10" rows="5" required></textarea>
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
    <script>
        let routeGetJenisSurat = "{{ route('pengajuangeoletter.getjenissurat') }}";
    </script>
    @vite('resources/views/script_view/pengajuan_geoletter/tambah_pengajuan.js')
@endsection
