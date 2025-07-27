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
                        <a href="#">Referensi</a>
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
                                <div id="editor-loading" class="text-center">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <textarea id="editor" name="editor" style="height: 700px;">{!! $dataJenisSurat->default_form !!}</textarea>
                                <div class="error-container" id="error-quil"></div>
                            </div>
                            <div>
                                <label for="is_datapendukung" class="form-label">Apakah perlu data pendukung ? <span class="text-danger">*</span></label>
                                <div class="form-check form-check-primary form-switch">
                                    <input class="form-check-input" name="is_datapendukung" type="checkbox" id="flexSwitchCheckChecked" value="1" <?= $dataJenisSurat->is_datapendukung? 'checked':'' ?> >
                                </div>
                            </div>
                            <div <?= $dataJenisSurat->is_datapendukung? '':'style="display: none;"'?>  id="div_keterangan_datadukung">
                                <label for="keterangan_datadukung" class="form-label">Keterangan data pendukung <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="keterangan_datadukung" name="keterangan_datadukung" placeholder="Keterangan data pendukung" value="{{ $dataJenisSurat->nama_datapendukung }}" autocomplete="off">
                            </div>
                            <div>
                                <label for="tingkat persetujuan" class="form-label" style="line-height: 2">Tingkat Persetujuan <?= ($pihakPenyetuju->count() < 1) ? '&nbsp;<span id="btnTambahPersetujuan" class="btn btn-sm btn-primary float-end" data-bs-toggle="modal" data-bs-target="#modal-tambahpersetujuan">+ Tambah Persetujuan</span>':'' ?></label>
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <div class="d-flex align-items-center gap-2 flex-wrap"><span class="text-success small fw-semibold">1. Admin {{ $namaLayananSurat }}</span></div>
                                </div>
                                @foreach($pihakPenyetuju as $penyetuju)
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <div class="d-flex align-items-center gap-2 flex-wrap"><span class="text-success small fw-semibold">
                                            {{ $penyetuju->urutan.'. '.$penyetuju->nama }}</span>
                                            <i class="small text-secondary">(<span>{{ $penyetuju->userpenyetuju->name }}</span>)</i>
                                        </div>
                                        <span class="bx bx-edit text-warning cursor-pointer" data-nama_pihakpenyetuju="{{ $penyetuju->userpenyetuju->name }}" data-nama_penyetuju="{{ $penyetuju->nama }}" data-id_penyetuju="{{ $penyetuju->id_penyetuju }}" data-id_pihakpenyetuju="{{ $penyetuju->id_pihakpenyetuju }}" data-bs-toggle="modal" data-bs-target="#modal-updatepersetujuan"></span>
                                        <span class="bx bx-x text-danger cursor-pointer" data-id_pihakpenyetuju="{{ $penyetuju->id_pihakpenyetuju }}" data-bs-toggle="modal" data-bs-target="#modal-hapuspersetujuan"></span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-12">
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
    <div class="modal fade" id="modal-tambahpersetujuan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <form action="{{ route('jenissurat.dotambahpenyetuju') }}" id="frm_tambahpersetujuan" method="POST">
                @csrf
                <input type="hidden" name="id_jenissurat" value="{{ $idJenisSurat }}" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel2">Tambah Persetujuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-6">
                            <div>
                                <label for="nama_persetujuan" class="form-label">Nama Persetujuan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_persetujuan" name="nama_persetujuan" placeholder="Nama persetujuan" required autocomplete="off" autofocus>
                            </div>
                            <div>
                                <label for="user_penyetuju" class="form-label">User Penyetuju <span class="text-danger">*</span></label>
                                <select name="user_penyetuju" id="user_penyetuju" class="form-control" required></select>
                                <div class="error-container" id="error-user_penyetuju"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Tambah Penyetuju</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="modal-updatepersetujuan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <form action="{{ route('jenissurat.doupdatepenyetuju') }}" id="frm_updatepersetujuan" method="POST">
                @csrf
                <input type="hidden" name="id_pihakpenyetujusurat" id="id_pihakpenyetujusurat_update" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Persetujuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-6">
                            <div>
                                <label for="nama_persetujuan" class="form-label">Nama Persetujuan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_persetujuan_update" name="nama_persetujuan" placeholder="Nama persetujuan" required autocomplete="off" autofocus>
                            </div>
                            <div>
                                <label for="user_penyetuju" class="form-label">User Penyetuju <span class="text-danger">*</span></label>
                                <select name="user_penyetuju" id="user_penyetuju_update" class="form-control" required></select>
                                <div class="error-container" id="error-user_penyetuju_update"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Update Penyetuju</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="modal-hapuspersetujuan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <form action="{{ route('jenissurat.dohapuspenyetuju') }}" method="POST">
                @csrf
                <input type="hidden" name="id_pihakpenyetujusurat" id="id_pihakpenyetujusurat" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel2">Hapus Penyetuju Surat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah yakin menghapus penyetuju ini?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('page-script')
    <script>
        const urlGetUser = '{{ route('jenissurat.getuserpenyetuju') }}';
        const urlGetUserUpdate = '{{ route('jenissurat.getuserpenyetujuupdate') }}';
        const idJenisSurat = '{{ $idJenisSurat }}';
    </script>
    @vite('resources/views/script_view/jenis_surat/edit_jenissurat.js')
@endsection
