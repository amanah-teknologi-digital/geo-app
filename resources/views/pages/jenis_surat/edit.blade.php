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
                    <li class="breadcrumb-item active">Edit</li>
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
                    <h5 class="card-title mb-0"><i class="bx bx-edit-alt"></i>&nbsp;Edit Jenis Surat</h5>
                    <a href="{{ route('jenissurat') }}" class="btn btn-sm btn-secondary btn-sm">
                        <i class="bx bx-arrow-back"></i>&nbsp;Kembali
                    </a>
                </div>
                <div class="card-body pt-4">
                    <form id="formJenisSurat" method="POST" action="{{ route('jenissurat.doedit') }}">
                        @csrf
                        <input type="hidden" name="id_jenissurat" value="{{ $dataJenisSurat->id_jenissurat }}">
                        <div class="row g-6">
                            <div>
                                <label for="nama_jenis" class="form-label">Nama Jenis Surat <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_jenis" name="nama_jenis" placeholder="Nama jenis surat" value="{{ $dataJenisSurat->nama }}" required autocomplete="off" autofocus>
                            </div>
                            <div>
                                <label for="isi_template" class="form-label">Template Surat <span class="text-danger">*</span></label>
{{--                                <div id="editor_template" style="height: 700px;">{!! $dataJenisSurat->default_form !!}</div>--}}
                                <div id="editor-loading" class="text-center">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <textarea id="editor" style="height: 700px;"></textarea>
                                <input type="hidden" name="editor_quil" id="editor_quil" value="{{ $dataJenisSurat->default_form }}">
                                <div class="error-container" id="error-quil"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-6">
                            <button type="submit" class="btn btn-warning me-3 text-black"><i class="bx bx-save"></i>&nbsp;Update Jenis Surat</button>
                            <div class="text-muted">
                                <small>
                                    Updated by: <strong>{{ $dataJenisSurat->pihakupdater->name }}</strong> | <span>{{ ($dataJenisSurat->updated_at ?? $dataJenisSurat->created_at)->format('d-m-Y H:i') }}</span>
                                </small>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-script')
    @vite('resources/views/script_view/jenis_surat/edit_jenissurat.js')
@endsection
