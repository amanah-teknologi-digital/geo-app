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
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center pb-4 border-bottom">
                    <h5 class="card-title mb-0"><i class="bx bx-building-house mb-1"></i>&nbsp;Detail Ruangan</h5>
                    <a href="{{ route('ruangan') }}" class="btn btn-sm btn-secondary btn-sm mb-0">
                        <i class="bx bx-arrow-back"></i>&nbsp;Kembali
                    </a>
                </div>
                <div class="card-body pt-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-6 gap-2">
                        <div class="me-1">
                            <h5 class="mb-0">{{ $dataRuangan->nama }}&nbsp;<span class="badge rounded-pill <?= $dataRuangan->is_aktif? 'bg-success':'bg-danger' ?> mb-3">{{ $dataRuangan->kode_ruangan }}</span></h5>
                            <p class="mb-0 w-100 text-truncate">{{ $dataRuangan->deskripsi }}</p>
                        </div>
                        <div class="d-flex align-items-center">
                            @if($dataRuangan->is_aktif)
                                <a class="btn btn-sm btn-primary d-flex align-items-center" href="{{ route('ruangan.detail', $dataRuangan->id_ruangan) }}"> <i class="icon-base bx bx-cart-add icon-sm lh-1 scaleX-n1-rtl me-2"></i><span class="me-2">Booking Ruangan</span></a>
                            @else
                                <a class="disabled btn btn-sm btn-danger d-flex align-items-center" href="javascript:void(0)"> <i class="icon-base bx bx-x icon-sm lh-1 scaleX-n1-rtl me-2"></i><span>Tidak Tersedia</span> </a>
                            @endif
{{--                            <span class="badge bg-label-danger">UI/UX</span>--}}
{{--                            <i class="icon-base bx bx-share-alt icon-lg mx-4"></i>--}}
{{--                            <i class="icon-base bx bx-bookmarks icon-lg"></i>--}}
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
                        <div class="card-body pt-4">
                            <div class="row">
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modals-transparent">
                                        <img class="img-fluid w-100" style="border-radius: 8px;object-fit: cover;" src="{{ $imageUrl }}" alt="{{ $dataRuangan->nama }}">
                                    </a>
                                    <div class="row align-items-center gx-4 mt-4">
                                        <div class="col-12 d-flex flex-wrap gap-2 justify-content-between">
                                            <span class="badge bg-label-primary small" style="font-size: 0.8125rem !important;">{{ $dataRuangan->jenis_ruangan }}</span>
                                            <p class="text-nowrap mb-2"><i class="icon-base bx bx-group me-2 align-bottom"></i>Kapasitas: {{ $dataRuangan->kapasitas }} Orang</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h5>Lokasi</h5>
                                    <p class="mb-0">{{ $dataRuangan->lokasi }}</p>
                                    <hr class="my-6">
                                    <h5>Fasilitas</h5>
                                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-2 row-cols-xxl-3 g-2">
                                        @foreach(json_decode($dataRuangan->fasilitas, true) as $item)
                                            <div class="col">
                                                <p class="mb-2"><i class="icon-base bx <?= $item['icon'] ?> me-2 align-bottom"></i>&nbsp;{{ $item['text'] }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                    <hr class="my-6">
                                    <h5>Keterangan</h5>
                                    <p class="mb-0">{!! nl2br(e($dataRuangan->keterangan)) !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <ul class="fa-ul ml-auto float-end mt-5">
                        <li>
                            <small><em>Hanya ruangan berstatus <b>aktif</b> yang bisa dibooking!.</em></small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
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
    @vite('resources/views/script_view/ruangan/detail_ruangan.js')
@endsection
