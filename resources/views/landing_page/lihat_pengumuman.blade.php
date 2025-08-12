@php use Illuminate\Support\Facades\Storage; @endphp
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>GeoReserve &bullet; {{ $data->judul }}</title>
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
{{--                    <a href="/" class="btn btn-sm btn-nav-daftar"><i class="bi bi-arrow-left me-1"></i> Landing Page</a>--}}&nbsp;
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
    <section id="main-section" class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <!-- Main Content -->
                <div class="col-lg-8 col-md-10 col-12">
                    <div class="content-wrapper shadow-sm p-4 rounded bg-white" data-aos="fade-up">
                        <!-- Judul -->
                        <h4 class="fw-bold mb-3 text-center">{{ $data->judul }}</h4>

                        <!-- Meta Data -->
                        <div class="news-meta d-flex flex-wrap justify-content-center gap-3 text-muted mb-4 small">
                            <span><i class="bi bi-calendar"></i> {{ $data->tgl_posting->format('d M Y') }}</span>
                            <span><i class="bi bi-person"></i> Ditulis oleh <b>{{ $data->postinger_user->name }}</b></span>
                        </div>

                        <!-- Gambar Pengumuman -->
                        @php
                            $file = $data->gambar_header;
                            $filePath = $data->file_pengumuman->location ?? null;
                            $imageUrl = ($filePath && Storage::disk('public')->exists($filePath))
                                ? route('file.getpublicfile', $file)
                                : asset('assets/img/no_image.jpg');
                        @endphp
                        <div class="text-center mb-4">
                            <img src="{{ $imageUrl }}"
                                 alt="{{ $data->judul }}"
                                 class="img-fluid rounded shadow-sm pengumuman-img">
                        </div>

                        <!-- Konten Pengumuman -->
                        <div id="editor_pengumuman"
                             class="content-body"
                             style="border: none; padding: 0;">
                            {!! $data->data !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .pengumuman-img {
            max-width: 80%;
            height: auto;
        }
        .content-body p {
            line-height: 1.7;
            font-size: 1rem;
        }
        @media (max-width: 576px) {
            .pengumuman-img {
                max-width: 100%;
            }
        }
    </style>

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
