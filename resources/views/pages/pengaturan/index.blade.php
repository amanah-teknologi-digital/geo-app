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
                <!-- Account -->
                <h5 class="card-header pb-4 border-bottom"><span class="tf-icons bx bx-edit"></span>&nbsp;Pengaturan Landing Page</h5>
                <div class="card-body pt-4">
                    <form id="formPengaturanLandingPage" method="POST" action="{{ route('pengaturan.update') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-6">
                            <div>
                                <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="alamat" id="alamat" cols="30" rows="10" required>{{ $dataPengaturan->alamat }}</textarea>
                            </div>
                            <div>
                                <label for="admin_geoletter" class="form-label">Admin Geo Letter <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="admin_geoletter" name="admin_geoletter" placeholder="Masukkan nama (no wa)" value="{{ $dataPengaturan->admin_geoletter }}" required autocomplete="off">
                            </div>
                            <div>
                                <label for="admin_ruang" class="form-label">Admin Geo Ruangan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="admin_ruang" name="admin_ruang" placeholder="Masukkan nama (no wa)" value="{{ $dataPengaturan->admin_ruang }}" required autocomplete="off">
                            </div>
                            <div>
                                <label for="admin_peralatan" class="form-label">Admin Geo Peralatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="admin_peralatan" name="admin_peralatan" placeholder="Masukkan nama (no wa)" value="{{ $dataPengaturan->admin_peralatan }}" required autocomplete="off">
                            </div>
                            <div>
                                <label for="link_yt" class="form-label">Link Youtube <span class="text-danger">*</span><i>(jika kosong isi dengan #)</i></label>
                                <input type="text" class="form-control" id="link_yt" name="link_yt" placeholder="link youtube" value="{{ $dataPengaturan->link_yt }}" required autocomplete="off">
                            </div>
                            <div>
                                <label for="link_fb" class="form-label">Link Facebook <span class="text-danger">*</span><i>(jika kosong isi dengan #)</i></label>
                                <input type="text" class="form-control" id="link_fb" name="link_fb" placeholder="link facebook" value="{{ $dataPengaturan->link_fb }}" required autocomplete="off">
                            </div>
                            <div>
                                <label for="link_ig" class="form-label">Link Instagram <span class="text-danger">*</span><i>(jika kosong isi dengan #)</i></label>
                                <input type="text" class="form-control" id="link_ig" name="link_ig" placeholder="link instagram" value="{{ $dataPengaturan->link_ig }}" required autocomplete="off">
                            </div>
                            <div>
                                <label for="link_linkedin" class="form-label">Link LinkedIn <span class="text-danger">*</span><i>(jika kosong isi dengan #)</i></label>
                                <input type="text" class="form-control" id="link_linkedin" name="link_linkedin" placeholder="link linkedin" value="{{ $dataPengaturan->link_linkedin }}" required autocomplete="off">
                            </div>
                            <div>
                                <label for="file_sop_geoletter" class="form-label">Unggah SOP GEO Letter <span class="text-muted"><i><b>(File gambar atau pdf max 10 mb)</b></i></span></label>
                                @php
                                    $filePath = optional($dataPengaturan->files_geoletter)->location ?? 'no-exist';
                                    $fileId = optional($dataPengaturan->files_geoletter)->id_file ?? -1;
                                    $imageUrl = Storage::disk('public')->exists($filePath)
                                        ? route('file.getpublicfile', $fileId)
                                        : false;
                                @endphp
                                <div class="d-flex align-items-center gap-2">
                                    @if(!$imageUrl)
                                        <p class="text-warning"><i>File belum ada!</i></p>
                                    @else
                                        <span class="text-success">{{ $dataPengaturan->files_geoletter->file_name. ' ('.formatBytes($dataPengaturan->files_geoletter->file_size).')' }}</span>
                                        <a href="{{ $imageUrl }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            Lihat file
                                        </a>
                                    @endif
                                </div>

                                <p class="text-muted mt-4" style="font-style: italic; font-size: smaller">klik tombol dibawah untuk mengubah file!</p>
                                <input type="file" class="form-control" id="file_sop_geoletter" name="file_sop_geoletter" accept="image/*,.pdf">
                            </div>
                            <div>
                                <label for="file_sop_georoom" class="form-label">Unggah SOP GEO Room <span class="text-muted"><i><b>(File gambar atau pdf max 10 mb)</b></i></span></label>
                                @php
                                    $filePath = optional($dataPengaturan->files_georoom)->location ?? 'no-exist';
                                    $fileId = optional($dataPengaturan->files_georoom)->id_file ?? -1;
                                    $imageUrl = Storage::disk('public')->exists($filePath)
                                        ? route('file.getpublicfile', $fileId)
                                        : false;
                                @endphp
                                <div class="d-flex align-items-center gap-2">
                                    @if(!$imageUrl)
                                        <p class="text-warning"><i>File belum ada!</i></p>
                                    @else
                                        <span class="text-success">{{ $dataPengaturan->files_georoom->file_name. ' ('.formatBytes($dataPengaturan->files_georoom->file_size).')' }}</span>
                                        <a href="{{ $imageUrl }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            Lihat file
                                        </a>
                                    @endif
                                </div>

                                <p class="text-muted mt-4" style="font-style: italic; font-size: smaller">klik tombol dibawah untuk mengubah file!</p>
                                <input type="file" class="form-control" id="file_sop_georoom" name="file_sop_georoom" accept="image/*,.pdf">
                            </div>
                            <div>
                                <label for="file_sop_geofacility" class="form-label">Unggah SOP GEO Facility <span class="text-muted"><i><b>(File gambar atau pdf max 10 mb)</b></i></span></label>
                                @php
                                    $filePath = optional($dataPengaturan->files_geofacility)->location ?? 'no-exist';
                                    $fileId = optional($dataPengaturan->files_geofacility)->id_file ?? -1;
                                    $imageUrl = Storage::disk('public')->exists($filePath)
                                        ? route('file.getpublicfile', $fileId)
                                        : false;
                                @endphp
                                <div class="d-flex align-items-center gap-2">
                                    @if(!$imageUrl)
                                        <p class="text-warning"><i>File belum ada!</i></p>
                                    @else
                                        <span class="text-success">{{ $dataPengaturan->files_geofacility->file_name. ' ('.formatBytes($dataPengaturan->files_geofacility->file_size).')' }}</span>
                                        <a href="{{ $imageUrl }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            Lihat file
                                        </a>
                                    @endif
                                </div>

                                <p class="text-muted mt-4" style="font-style: italic; font-size: smaller">klik tombol dibawah untuk mengubah file!</p>
                                <input type="file" class="form-control" id="file_sop_geofacility" name="file_sop_geofacility" accept="image/*,.pdf">
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="btn btn-primary me-3">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
                <!-- /Account -->
            </div>
        </div>
    </div>
@endsection
@section('page-script')
    @vite('resources/views/script_view/update_pengaturan.js')
@endsection
