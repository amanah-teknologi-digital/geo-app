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
                    <h5 class="card-title mb-0"><i class="bx bx-edit-alt"></i>&nbsp;Edit Pengumuman</h5>
                    <a href="{{ route('pengumuman') }}" class="btn btn-sm btn-secondary btn-sm">
                        <i class="bx bx-arrow-back"></i>&nbsp;Kembali
                    </a>
                </div>
                <div class="card-body pt-4">
                    <form id="formPengumuman" method="POST" action="{{ route('pengumuman.doedit') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_pengumuman" value="{{ $dataPengumuman->id_pengumuman }}">
                        <div class="row g-6">
                            <div>
                                <label for="judul" class="form-label">Judul Pengumuman <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="judul" name="judul" placeholder="judul" value="{{ $dataPengumuman->judul }}" required autocomplete="off" autofocus {{ !$is_edit ? 'readonly':'' }}>
                            </div>
                            <div>
                                <label for="isi_pengumuman" class="form-label">Isi Pengumuman <span class="text-danger">*</span></label>
                                <div id="editor-loading" class="text-center">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <textarea id="editor_pengumuman" name="editor_pengumuman" style="height: 500px;">{!! $dataPengumuman->data !!}</textarea>
                                <div class="error-container" id="error-quil"></div>
                            </div>
                            <div>
                                <label for="gambar_header" class="form-label">Gambar Header <span class="text-danger">*</span><span class="text-muted"><i><b>(File gambar max 5 mb)</b></i></span></label>
                                @php
                                    $file = $dataPengumuman->gambar_header;
                                    $filePath = $dataPengumuman->file_pengumuman->location;
                                    $imageUrl = Storage::disk('public')->exists($filePath)
                                        ? route('file.getpublicfile', $file)
                                        : asset('assets/img/no_image.jpg');
                                @endphp
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $imageUrl }}" class="d-block h-px-100 rounded">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modals-transparent">
                                        Lihat file
                                    </button>
                                </div>
                                @if($is_edit)
                                    <p class="text-muted mt-4" style="font-style: italic; font-size: smaller">klik tombol dibawah untuk mengubah file!</p>
                                    <input type="file" class="form-control" id="gambar_header" name="gambar_header" accept="image/*">
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-6">
                            @if(!$is_edit)
                                <div class="text-muted">
                                    <small>
                                        Posting by: <strong>{{ $dataPengumuman->postinger_user->name }}</strong> | <span>{{ $dataPengumuman->tgl_posting->format('d-m-Y H:i') }}</span>
                                    </small>
                                </div>
                            @else
                                <button type="submit" class="btn btn-warning text-black me-3"><i class="bx bx-save"></i>&nbsp;Update Pengumuman</button>
                            @endif
                            <div class="text-muted">
                                <small>
                                    Updated by: <strong>{{ $dataPengumuman->user->name }}</strong> | <span>{{ ($dataPengumuman->updated_at ?? $dataPengumuman->created_at)->format('d-m-Y H:i') }}</span>
                                </small>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal modal-transparent fade" id="modals-transparent" tabindex="-1" style="border: none;">
        <div class="modal-dialog">
            <div class="modal-content" style="background: rgba(0, 0, 0, 0);border: none;color: white;">
                <div class="modal-body">
                    <img id="kartu_idmodal" src="{{ $imageUrl }}" class="img-fluid w-100 h-100 object-fit-cover" alt="kartu ID">
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-script')
    <script>
        const isEdit = {{ $is_edit ? 'true' : 'false' }};
    </script>
    @vite('resources/views/script_view/edit_pengumuman.js')
@endsection
