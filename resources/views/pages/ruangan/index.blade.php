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
                <ol class="breadcrumb flex">
                    <li class="breadcrumb-item">
                        <a href="#">Master Data</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
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
                <div class="card-body">
                    <div class="mb-5 pb-4 border-bottom d-flex justify-content-between align-items-center">
                        <div class="head-label text-center">
                            <span class="card-header p-0"><i class="tf-icons bx bx-book-content"></i>&nbsp;List Ruangan</span>
                        </div>
                        @if($isTambah)
                            <div class="dt-action-buttons text-end">
                                <div class="dt-buttons btn-group flex-wrap">
                                    <div class="btn-group">
                                        <a href="{{ route('ruangan.tambah') }}" class="btn btn-secondary create-new btn-sm btn-primary" ><span><i class="icon-base bx bx-plus me-1"></i> <span class="d-none d-lg-inline-block">Tambah Ruangan</span></span></a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="row gy-6 mb-6">
                        @if($dataRuangan->isNotEmpty())
                            @foreach($dataRuangan as $ruang)
                                @php
                                    $file = $ruang->gambar_file;
                                    $filePath = $ruang->gambar->location;
                                    $imageUrl = Storage::disk('public')->exists($filePath)
                                        ? route('file.getpublicfile', $file)
                                        : asset('assets/img/no_image.jpg');
                                    $fasilitas = json_decode($ruang->fasilitas, true);
                                @endphp
                                <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xxl-3">
                                    <div class="card p-2 h-100 shadow border mh-100">
                                        <div class="rounded-2 p-2 text-center mb-4 border-bottom">
                                            <a href="{{ route('ruangan.detail', $ruang->id_ruangan) }}"><img class="img-fluid " style="height: 9rem;aspect-ratio: 4 / 3;border-radius: 8px;object-fit: cover;width: 100%;" src="{{ $imageUrl }}" alt="{{ $ruang->nama }}"></a>
                                        </div>
                                        <div class="card-body p-2 pt-0">
                                            <a href="{{ route('ruangan.detail', $ruang->id_ruangan) }}" class="h6 w-100 text-truncate">{{ $ruang->nama }}&nbsp;<span class="badge rounded-pill <?= $ruang->is_aktif? 'bg-success':'bg-danger' ?> mb-3">{{ $ruang->kode_ruangan }}</span></a>
                                            <div class="d-flex justify-content-between align-items-center mb-4">
                                                <span class="badge bg-label-primary small" style="font-size: 0.8125rem !important;">{{ $ruang->jenis_ruangan }}</span>
                                                <p class="d-flex align-items-center justify-content-center fw-medium gap-1 mb-0">
                                                    <span class="fw-normal small w-100 text-muted">Kapasitas {{ $ruang->kapasitas }} Orang</span>
                                                </p>
                                            </div>
                                            <p class="mt-1 small text-truncate w-100 fst-italic">{{ $ruang->lokasi }}</p>
                                            <div class="row align-items-center gx-4">
                                                <div class="col-12 d-flex flex-wrap gap-2 justify-content-between">
                                                    @if($ruang->is_aktif)
                                                        <a class="btn btn-sm btn-outline-success d-flex align-items-center" href="{{ route('ruangan.jadwal', $ruang->id_ruangan) }}"> <i class="icon-base bx bx-calendar icon-sm lh-1 scaleX-n1-rtl me-2"></i><span>Jadwal</span> </a>
                                                    @else
                                                        <a class="disabled btn btn-sm btn-danger d-flex align-items-center" href="javascript:void(0)"> <i class="icon-base bx bx-x icon-sm lh-1 scaleX-n1-rtl me-2"></i><span>Tidak Tersedia</span> </a>
                                                    @endif
                                                    <a class="btn btn-sm btn-primary d-flex align-items-center" href="{{ route('ruangan.detail', $ruang->id_ruangan) }}"> <span class="me-2">Detail</span><i class="icon-base bx bx-chevron-right icon-sm lh-1 scaleX-n1-rtl"></i> </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center">
                                <p class="text-muted">Data Ruangan Kosong!</p>
                            </div>
                        @endif
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
@endsection
{{--@section('page-script')--}}
{{--    <script>--}}
{{--        let title = "{{ $title }}";--}}
{{--        let routeName = "{{ route('jenissurat.getdata') }}"; // Ensure route name is valid--}}
{{--        let routeTambah = "{{ route('jenissurat.tambah') }}"--}}
{{--    </script>--}}
{{--    @vite('resources/views/script_view/ruangan/list_ruangan.js')--}}
{{--@endsection--}}
