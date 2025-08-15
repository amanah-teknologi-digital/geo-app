@php use Illuminate\Support\Facades\Storage; @endphp
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>GeoReserve &bullet; Ruangan {{ $dataRuangan->nama }}</title>
    <meta name="description" content="Sistem Informasi Manajemen Geofisika ITS (GeoReserve) untuk pengajuan persuratan, booking ruangan, peminjaman peralatan, dan informasi terbaru. Efisien, cepat, dan terintegrasi.">
    <meta name="keywords" content="sistem informasi, manajemen, persuratan, booking ruangan, peminjaman peralatan, georeserve, ITS, Geofisika">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicons -->
    <link href="{{ asset('landing_page_rss/teknikgeo.png') }}" rel="icon">
    <link href="{{ asset('landing_page_rss/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts (Poppins) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('landing_page_rss/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('landing_page_rss/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="{{ asset('landing_page_rss/assets/vendor/aos/aos.css') }}" rel="stylesheet">

    @vite([
        'resources/assets/vendor/scss/core.scss',
        'resources/assets/vendor/libs/fullcalendar/fullcalendar.scss',
        'resources/assets/vendor/scss/pages/page-auth.scss',
        'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
        'resources/assets/css/custom.scss'
    ])

    <!-- Main CSS File -->
    <link href="{{ asset('landing_page_rss/assets/css/main.css') }}" rel="stylesheet">
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a href="{{ route('landingpage') }}" class="logo d-flex align-items-center me-auto me-xl-0 order-first">
            <img src="{{ asset('landing_page_rss/teknikgeo.png') }}" alt="" class="logo-img">
            <h1 class="sitename">GeoReserve</h1>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    &nbsp;
                </li>
            </ul>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-success me-2 rounded-3"><i class="bi bi-house"></i>&nbsp;Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-sm btn-nav-masuk me-2"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-sm btn-nav-daftar"><i class="bi bi-person-plus-fill me-1"></i> Daftar</a>
            @endif
        </div>
    </div>
</nav>

<main>
    <!-- Main Content & Sidebar Section -->
    <header id="hero" class="ruangan-section pt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="card-custom mb-5" data-aos="fade-up" data-aos-delay="100">
                        <div class="card-header-custom d-flex justify-content-between align-items-center pb-4 border-bottom">
                            <h5 class="card-title mb-0"><i class="bx bx-detail mb-1"></i>&nbsp;Detail {{ $dataRuangan->nama }} &nbsp;<span class="badge bg-success rounded-pill align-middle" style="font-size: 0.7rem;">{{ $dataRuangan->kode_ruangan }}</span></h5>
                            <a href="{{ route('listruangan') }}" class="btn btn-light mb-0">
                                <i class="bx bx-arrow-back"></i>&nbsp;Kembali
                            </a>
                        </div>
                        <div class="card-body p-5">
                            @php
                                $file = $dataRuangan->gambar_file;
                                $filePath = $dataRuangan->gambar->location;
                                $imageUrl = Storage::disk('public')->exists($filePath)
                                    ? route('file.getpublicfile', $file)
                                    : asset('assets/img/no_image.jpg');
                            @endphp
                            <div class="row mb-5">
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <div class="position-relative">
                                        <img src="{{ $imageUrl }}" class="card-img-top rounded-2" alt="{{ $dataRuangan->nama }}">
                                        <span class="badge bg-label position-absolute top-0 end-0 m-3">{{ $dataRuangan->jenis_ruangan }}</span>
                                    </div>

                                    <div class="row align-items-center gx-4 mt-4">
                                        <div class="col-12 d-flex flex-wrap gap-2 justify-content-between">
                                            <div class="facility-item">
                                                <i class="bx bx-user"></i>
                                                <span>Kapasitas {{ $dataRuangan->kapasitas }} Orang</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h5>Lokasi <span class="badge bg-label-primary small" style="font-size: 0.8125rem !important;">{{ $dataRuangan->jenis_ruangan }}</span></h5>
                                    <p class="mb-0">{{ $dataRuangan->lokasi }}</p>
                                    <hr class="my-6">
                                    <h5>Fasilitas</h5>
                                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-2 row-cols-xxl-3 g-2 align-items-center">
                                        @foreach(json_decode($dataRuangan->fasilitas, true) as $item)
                                            <div class="col">
                                                <div class="facility-item mb-2">
                                                    <i class="bx {{ $item['icon'] }} me-2 align-bottom"></i>
                                                    <span>{{ $item['text'] }}</span>
                                                </div>
                                                {{--                                        <p class="mb-2"><i class="icon-base bx <?= $item['icon'] ?> me-2 align-bottom"></i>&nbsp;{{ $item['text'] }}</p>--}}
                                            </div>
                                        @endforeach
                                    </div>
                                    <hr class="my-6">
                                    <h5>Keterangan</h5>
                                    <p class="mb-0 text-muted fst-italic">{!! nl2br(e($dataRuangan->keterangan)) !!}</p>
                                </div>
                            </div>
                            <div class="card shadow-none app-calendar-wrapper border-top border-bottom">
                                <div class="row g-0">
                                    <div class="col app-calendar-sidebar border-end" id="app-calendar-sidebar">
                                        <div class="pb-2 my-sm-0 pt-4">
                                            <!-- Filter -->
                                            <div>
                                                <h5>Filter Jadwal</h5>
                                            </div>

                                            <div class="form-check form-check-secondary mb-5 ms-2">
                                                <input class="form-check-input select-all" type="checkbox" id="selectAll" data-value="all" checked="">
                                                <label class="form-check-label" for="selectAll">Tampilkan Semua</label>
                                            </div>

                                            <div class="app-calendar-events-filter text-heading">
                                                <div class="form-check form-check-success mb-5 ms-2">
                                                    <input class="form-check-input input-filter" type="checkbox" id="select-jadwal" data-value="jadwal" checked="">
                                                    <label class="form-check-label" for="select-jadwal">Jadwal Kuliah</label>
                                                </div>
                                                <div class="form-check form-check-primary mb-5 ms-2">
                                                    <input class="form-check-input input-filter" type="checkbox" id="select-booking" data-value="booking" checked="">
                                                    <label class="form-check-label" for="select-booking">Jadwal Booking</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col app-calendar-content">
                                        <div class="card shadow-none border-0">
                                            <div class="card-body pb-0">
                                                <div id="calendar"></div>
                                            </div>
                                        </div>
                                        <div class="app-overlay"></div>
                                    </div>
                                </div>
                            </div>
                            <ul class="fa-ul ml-auto float-end mt-5 p-4">
                                <li>
                                    <small><em>Pembokingan harap H-1 dari pembookingan, hanya jadwal yang tersedia saja yang bisa dibooking!.</em></small>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="offcanvas offcanvas-end event-sidebar" tabindex="-1" id="addEventSidebarUpdate" aria-labelledby="addEventSidebarUpdateLabel" >
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title" id="addEventSidebarUpdateLabel">Detail Jadwal</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body" data-select2-id="7">
                <div class="mb-6">
                    <label class="form-label" for="keterangan_update">Keterangan Jadwal <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="keterangan_update" name="keterangan" autocomplete="off" autofocus placeholder="keterangan jadwal" required>
                </div>
                <div class="mb-6">
                    <label class="form-label" for="hari_update">Pilih Hari <span class="text-danger">*</span></label>
                    <select class="form-control" name="hari" id="hari_update" required>
                        <option value="" selected disabled>-- pilih hari --</option>
                        @foreach ($hari as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-6">
                    <label class="form-label" for="tgl_jadwal_update">Pilih Tanggal <span class="text-danger">*</span></label>
                    <input type="text" id="tgl_jadwal_update" class="form-control" name="tgl_jadwal" required placeholder="pilih tanggal mulai - selesai" autocomplete="off">
                </div>
                <div class="mb-6">
                    <label class="form-label" for="jam_jadwal_update">Pilih Waktu <span class="text-danger">*</span></label>
                    <div class="d-inline-flex gap-2">
                        <input type="text" id="jam_mulai_update" class="form-control jam_jadwal_update" name="jam_mulai" placeholder="pilih jam mulai" autocomplete="off">
                        <input type="text" id="jam_selesai_update" class="form-control jam_jadwal_update" name="jam_selesai" placeholder="pilih jam selesai" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
    </header>

</main>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <a href="" class="logo d-flex align-items-center me-auto me-xl-0 order-first">
                    <img src="{{ asset('landing_page_rss/teknikgeo.png') }}" alt="" class="logo-img">
                    <h1 class="sitename">GeoReserve</h1>
                </a>
                <p>Sistem Informasi Manajemen Geofisika yang membantu mengelola administrasi dan inventaris secara efisien dan transparan.</p>
                <div class="footer-social mt-4">
                    <a href="{{ $pengaturan->link_yt }}"><i class="bi bi-youtube"></i></a>
                    <a href="{{ $pengaturan->link_fb }}"><i class="bi bi-facebook"></i></a>
                    <a href="{{ $pengaturan->link_ig }}"><i class="bi bi-instagram"></i></a>
                    <a href="{{ $pengaturan->link_linkedin }}"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <h5 class="footer-title">Layanan</h5>
                <div class="footer-links">
                    <a href="#hero"><i class="bi bi-dot"></i>Pengajuan Surat</a>
                    <a href="#hero"><i class="bi bi-dot"></i>Booking Ruangan</a>
                    <a href="#hero"><i class="bi bi-dot"></i>Peminjaman Peralatan</a>
                </div>
            </div>
            <div class="col-lg-4">
                <h5 class="footer-title">Alamat Kantor</h5>
                <ul class="list-unstyled">
                    <li class="mb-2 d-flex"><i class="bi bi-geo-alt-fill me-2 mt-1"></i><span>{{ $pengaturan->alamat }}</span></li>
                    <li class="mb-2 d-flex"><i class="bi bi-envelope-fill me-2 mt-1"></i><span>tgeofisika@its.ac.id</span></li>
                    <li class="mb-2 d-flex"><i class="bi bi-telephone-fill me-2 mt-1"></i><span>(031) 5953476</span></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom text-center">
            <p class="mb-0">&copy; {{ date('Y') }} GeoReserve - Sistem Informasi Manajemen Geofisika. All Rights Reserved.</p>
        </div>
    </div>
</footer>
<script src="{{ asset('landing_page_rss/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('landing_page_rss/assets/vendor/aos/aos.js') }}"></script>

<!-- Main JS File -->
<script src="{{ asset('landing_page_rss/assets/js/main.js') }}"></script>
<script>
    const urlGetData = '{{ route('listruangan.getdatajadwal') }}';
    const idRuangan = '{{ $idRuangan }}';
</script>
@vite([
    'resources/assets/vendor/libs/jquery/jquery.js',
    'resources/assets/vendor/libs/fullcalendar/fullcalendar.js',
    'resources/assets/vendor/libs/flatpickr/flatpickr.js',
    'resources/views/script_view/landing_page/detail_ruangan.js'
])
</body>

</html>
