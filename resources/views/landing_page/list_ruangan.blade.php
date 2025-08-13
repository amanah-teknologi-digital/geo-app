@php use Illuminate\Support\Facades\Storage; @endphp
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>GeoReserve &bullet; List Ruangan</title>
    <meta name="description" content="Sistem Informasi Manajemen Geofisika ITS (GeoReserve) untuk pengajuan persuratan, booking ruangan, peminjaman peralatan, dan informasi terbaru. Efisien, cepat, dan terintegrasi.">
    <meta name="keywords" content="sistem informasi, manajemen, persuratan, booking ruangan, peminjaman peralatan, georeserve, ITS, Geofisika">

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
    <header id="hero" class="ruangan-section pt-3">
        <div class="container">
            <div class="card-custom mb-5" data-aos="fade-up" data-aos-delay="100">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="bi bi bi-calendar-week-fill me-2"></i>List Ruangan</h5>
                    <a href="{{ route('landingpage') }}" class="btn btn-light mb-0">
                        <i class="bx bx-arrow-back"></i>&nbsp;Landing Page
                    </a>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        @if(isset($dataRuangan) && !$dataRuangan->isEmpty())
                            @foreach($dataRuangan as $ruangan)
                                @php
                                    $file = $ruangan->gambar_file ?? null;
                                    $filePath = $ruangan->gambar->location ?? null;
                                    $imageUrl = ($filePath && Storage::disk('public')->exists($filePath))
                                        ? route('file.getpublicfile', $file)
                                        : asset('assets/img/no_image.jpg');
                                @endphp
                                <div class="col-xxl-3 col-lg-4 col-md-6">
                                    <div class="card room-card shadow-lg">
                                        <div class="position-relative">
                                            <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $ruangan->nama }}">
                                            <span class="badge bg-label position-absolute top-0 end-0 m-3">{{ $ruangan->jenis_ruangan }}</span>
                                        </div>
                                        <div class="card-body p-3">
                                            <h5 class="card-title">{{ $ruangan->nama }}&nbsp;<span class="badge bg-success rounded-pill align-middle" style="font-size: 0.7rem;">{{ $ruangan->kode_ruangan }}</span></h5>
                                            <p class="card-text mb-2 mt-2"><i class="bx bx-location-plus me-1"></i>{{ $ruangan->lokasi }}</p>

                                            <div class="facility-section">
                                                @php
                                                    $fasilitasList = json_decode($ruangan->fasilitas);
                                                @endphp

                                                <div class="facility-item">
                                                    <i class="bx bx-user text-primary"></i>
                                                    <p>{{ $ruangan->kapasitas }} Orang</p>
                                                </div>

                                                @if (!empty($fasilitasList))
                                                    @foreach ($fasilitasList as $fasilitas)
                                                        <div class="facility-item">
                                                            <i class="bx {{ $fasilitas->icon }} text-primary"></i>
                                                            <p>{{ $fasilitas->text }}</p>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>

                                            <a href="{{ route('listruangan.detail', $ruangan->id_ruangan) }}" class="btn btn-primary w-100"><i class="bx bx-detail me-1"></i>Lihat Detail</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <p class="text-center text-muted">Tidak ada ruangan yang tersedia saat ini.</p>
                            </div>
                        @endif
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
</body>

</html>
