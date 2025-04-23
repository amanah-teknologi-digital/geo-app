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
                        <h5 class="card-title mb-0"><i class="bx bx-plus mb-1"></i>&nbsp;Tambah Ruangan</h5>
                        <a href="{{ route('ruangan') }}" class="btn btn-sm btn-secondary btn-sm mb-0">
                            <i class="bx bx-arrow-back"></i>&nbsp;Kembali
                        </a>
                    </div>
                    <div class="card-body pt-4">
                        <form id="formRuangan" method="POST" action="{{ route('ruangan.dotambah') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-6">
                                <div>
                                    <label for="kode_ruangan" class="form-label">Kode Ruangan <span class="text-danger">*</span>&nbsp;<i class="text-muted"><b>(Contoh: TG-301)</b></i></label>
                                    <input type="text" class="form-control" id="kode_ruangan" name="kode_ruangan" placeholder="Kode Ruangan (harus unik dari lainya)" required autocomplete="off" autofocus>
                                </div>
                                <div>
                                    <label for="nama_ruangan" class="form-label">Nama Ruangan <span class="text-danger">*</span>&nbsp;<i class="text-muted"><b>(Contoh: Ruangan TG-301)</b></i></label>
                                    <input type="text" class="form-control" id="nama_ruangan" name="nama_ruangan" placeholder="Nama Ruangan" required autocomplete="off">
                                </div>
                                <div>
                                    <label for="jenis_ruangan" class="form-label">Jenis Ruangan <span class="text-danger">*</span></label>
                                    <select name="jenis_ruangan" id="jenis_ruangan" class="form-control" required>
                                        <option value="" selected disabled>-- Pilih Jenis Ruangan --</option>
                                        @foreach($dataJenisRuangan as $jenis)
                                            <option value="{{ $jenis->nama }}" >{{ $jenis->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="lokasi" class="form-label">Lokasi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="lokasi" id="lokasi" placeholder="Lokasi" required autocomplete="off">
                                </div>
                                <div>
                                    <label for="kapasitas" class="form-label">Kapasitas <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="kapasitas" id="kapasitas" placeholder="Kapasitas" required autocomplete="off">
                                </div>
                                <div>
                                    <label for="fasilitas" class="form-label">Fasilitas Ruangan <span class="text-danger">*</span>&nbsp;<i class="text-muted"><b>(Pilih minimal 1)</b></i></label>
                                    <select id="fasilitas" name="fasilitas[]" class="form-control" multiple="multiple" required>
                                        @foreach ($dataFasilitas as $kategori => $fasilitas)
                                            <optgroup label="{{ $kategori }}">
                                                @foreach ($fasilitas as $item)
                                                    <option value="{{ $item['id'] }}" data-icon="{{ $item['icon'] }}">{{ $item['text'] }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    <div class="error-container" id="error-fasilitas"></div>
                                </div>
                                <div>
                                    <label for="keterangan" class="form-label">Keterangan Ruangan <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="keterangan" id="keterangan" rows="20" placeholder="Ruangan ini disewakan untuk keperluan kegiatan seperti rapat, pelatihan, seminar, atau acara lainnya. Fasilitas yang tersedia meliputi: Kursi dan meja, AC, Proyektor dan layar, Sound system, Wi-Fi, Area parkir" required></textarea>
                                </div>
                                <div>
                                    <label for="gambar_ruangan" class="form-label">Gambar Ruangan <span class="text-danger">*</span><span class="text-muted"><i><b>(File gambar max 5 mb)</b></i></span></label>
                                    <input type="file" class="form-control" id="gambar_ruangan" name="gambar_ruangan" accept="image/*">
                                </div>
                            </div>
                            <div class="mt-6">
                                <button type="submit" class="btn btn-primary me-3"><i class="bx bx-save"></i>&nbsp;Tambah Ruangan</button>
                            </div>
                        </form>
                    </div>
            </div>
        </div>
    </div>
@endsection
@section('page-script')
    @vite('resources/views/script_view/ruangan/tambah_ruangan.js')
@endsection
