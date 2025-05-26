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
                    <form id="formRuangan" method="POST" action="{{ route('ruangan.doupdate') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_ruangan" value="{{ $idRuangan }}" required>
                        <div class="row g-6">
                            <div>
                                <label for="kode_ruangan" class="form-label">Kode Ruangan <span class="text-danger">*</span>&nbsp;<i class="text-muted"><b>(Contoh: TG-301)</b></i></label>
                                <input type="text" class="form-control" id="kode_ruangan" name="kode_ruangan" placeholder="Kode Ruangan (harus unik dari lainya)" value="{{ $dataRuangan->kode_ruangan }}" required autocomplete="off" autofocus>
                            </div>
                            <div>
                                <label for="nama_ruangan" class="form-label">Nama Ruangan <span class="text-danger">*</span>&nbsp;<i class="text-muted"><b>(Contoh: Ruangan TG-301)</b></i></label>
                                <input type="text" class="form-control" id="nama_ruangan" name="nama_ruangan" placeholder="Nama Ruangan" value="{{ $dataRuangan->nama }}" required autocomplete="off">
                            </div>
                            <div>
                                <label for="jenis_ruangan" class="form-label">Jenis Ruangan <span class="text-danger">*</span></label>
                                <select name="jenis_ruangan" id="jenis_ruangan" class="form-control" required>
                                    <option value="" selected disabled>-- Pilih Jenis Ruangan --</option>
                                    @foreach($dataJenisRuangan as $jenis)
                                        <option value="{{ $jenis->nama }}" {{ $jenis->nama == $dataRuangan->jenis_ruangan? 'selected':'' }}>{{ $jenis->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="lokasi" class="form-label">Lokasi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="lokasi" id="lokasi" placeholder="Lokasi" value="{{ $dataRuangan->lokasi }}" required autocomplete="off">
                            </div>
                            <div>
                                <label for="kapasitas" class="form-label">Kapasitas <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="kapasitas" id="kapasitas" placeholder="Kapasitas" value="{{ $dataRuangan->kapasitas }}" required autocomplete="off">
                            </div>
                            <div>
                                @php $selected = collect(json_decode($dataRuangan->fasilitas))->pluck('id')->toArray(); @endphp
                                <label for="fasilitas" class="form-label">Fasilitas Ruangan <span class="text-danger">*</span>&nbsp;<i class="text-muted"><b>(Pilih minimal 1)</b></i></label>
                                <select id="fasilitas" name="fasilitas[]" class="form-control" multiple="multiple" required>
                                    @foreach ($dataFasilitas as $kategori => $fasilitas)
                                        <optgroup label="{{ $kategori }}">
                                            @foreach ($fasilitas as $item)
                                                <option value="{{ $item['id'] }}" {{ in_array($item['id'], $selected) ? 'selected' : '' }} data-icon="{{ $item['icon'] }}">{{ $item['text'] }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <div class="error-container" id="error-fasilitas"></div>
                            </div>
                            <div>
                                <label for="is_aktif" class="form-label">Apakah aktif ? <span class="text-danger">*</span></label>
                                <div class="form-check form-check-primary form-switch">
                                    <input class="form-check-input" name="is_aktif" type="checkbox" id="flexSwitchCheckChecked" value="1" <?= $dataRuangan->is_aktif? 'checked':'' ?> >
                                </div>
                            </div>
                            <div>
                                <label for="keterangan" class="form-label">Keterangan Ruangan <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="keterangan" id="keterangan" rows="20" placeholder="Ruangan ini disewakan untuk keperluan kegiatan seperti rapat, pelatihan, seminar, atau acara lainnya. Fasilitas yang tersedia meliputi: Kursi dan meja, AC, Proyektor dan layar, Sound system, Wi-Fi, Area parkir" required>{{ $dataRuangan->keterangan }}</textarea>
                            </div>
                            <div>
                                <label for="gambar_ruangan" class="form-label">Gambar Ruangan <span class="text-danger">*</span><span class="text-muted"><i><b>(File gambar max 5 mb)</b></i></span></label>
                                @php
                                    $file = $dataRuangan->gambar_file;
                                    $filePath = $dataRuangan->gambar->location;
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
                                <p class="text-muted mt-4" style="font-style: italic; font-size: smaller">klik tombol dibawah untuk mengubah file!</p>
                                <input type="file" class="form-control" id="gambar_ruangan" name="gambar_ruangan" accept="image/*">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-6">
                            <button type="submit" class="btn btn-warning text-black me-3"><i class="bx bx-save"></i>&nbsp;Update Ruangan</button>
                            <div class="text-muted">
                                <small>
                                    Updated by: <strong>{{ $dataRuangan->pihakupdater->name }}</strong> | <span>{{ ($dataRuangan->updated_at ?? $dataRuangan->created_at)->format('d-m-Y H:i') }}</span>
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
    @vite('resources/views/script_view/ruangan/edit_ruangan.js')
@endsection
