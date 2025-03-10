@php use Illuminate\Support\Facades\Storage; @endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>List Pengumuman</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="{{ asset('landing_page_rss/teknikgeo.png') }}" rel="icon">
    <link href="{{ asset('landing_page_rss/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Amatic+SC:wght@400;700&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('landing_page_rss/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('landing_page_rss/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('landing_page_rss/assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('landing_page_rss/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('landing_page_rss/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('landing_page_rss/assets/css/main.css') }}" rel="stylesheet">

    <!-- =======================================================
    * Template Name: Yummy
    * Template URL: https://bootstrapmade.com/yummy-bootstrap-restaurant-website-template/
    * Updated: Aug 07 2024 with Bootstrap v5.3.3
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    ======================================================== -->
    <style>
        .content-wrapper {
            max-width: 1024px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
        }
    </style>
    <script>
        let routeName = "{{ route('pengumuman.getlistpengumuman') }}"
    </script>
    @vite([
        'resources/assets/vendor/libs/jquery/jquery.js',
        'resources/assets/vendor/libs/datatable/datatable.js',
        'resources/assets/vendor/libs/datatable/datatable.scss',
        'resources/views/script_view/list_landingpengumuman.js',
        ])
</head>

<body class="index-page">

<header id="header" class="header d-flex align-items-center sticky-top" style="color: var(--color-default);">
    <div class="container position-relative d-flex align-items-center justify-content-between" >

        <a href="" class="logo d-flex align-items-center me-auto me-xl-0 order-first">
            <img src="{{ asset('landing_page_rss/teknikgeo.png') }}" alt="">
            <h1 class="sitename">GeoReserve</h1>
        </a>

        <nav id="navmenu" class="navmenu d-flex align-content-center" >
            <ul>
                <li><a href="{{ route('landingpage') }}" class="active"><i class="bi bi-house-door-fill"></i>&nbsp;Halaman Utama<br></a></li>
                <li><a href="{{ route('landingpage') }}/#pengumuman"><i class="bi bi-newspaper"></i>&nbsp;Pengumuman</a></li>
                <li><a href="{{ route('landingpage') }}/#layanan"><i class="bi bi-info-circle-fill"></i>&nbsp;Layanan Support</a></li>
{{--                <li><a href="#footer"><i class="bi bi-people-fill"></i>&nbsp;Kontak</a></li>--}}
                @auth
                    <li>
                        <button class="btn btn-sm btn-primary w-100" style="background-color: var(--color-default)"><a style="color: white !important;" href="{{ route('dashboard') }}"><i class="bi bi-house"></i>&nbsp;Dashboard</a></button>
                    </li>
                @else
                    <li>
                        <button class="btn btn-sm btn-success w-100"><a href="{{ route('login') }}" style="color: white !important;"><i class="bi bi-box-arrow-in-right"></i>&nbsp;Login</a></button>
                    </li>
                    <li>
                        <button class="btn btn-sm btn-primary w-100" style="background-color: var(--color-default) !important;"><a style="color: white !important;" href="{{ route('register') }}"><i class="bi bi-journal-bookmark-fill"></i>&nbsp;Registrasi</a></button>
                    </li>
                @endauth
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list d-flex order-last"></i>
        </nav>
    </div>
</header>

<main class="main ">
    <section id="pengumuman" class="pengumuman section light-background" style="padding-top:50px; ">
        <div class="container mt-0">
            <div class="row justify-content-center" style="min-height: 50vh">
                <div class="content-wrapper shadow" data-aos="fade-up">
                    <div class="d-flex justify-content-between align-items-center flex-wrap flex-md-nowrap mb-3">
                        <h5 class="mb-0"><i class="bi bi-list"></i>&nbsp;List Pengumuman</h5>
                        <a href="{{ route('landingpage') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-box-arrow-left"></i> Kembali ke landing page
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" class="table">
                            <thead>
                            <tr>
                                <th style="border-top-width: 1px" nowrap class="text-center">No</th>
                                <th style="border-top-width: 1px" nowrap>Judul</th>
                                <th style="border-top-width: 1px" nowrap>Pembuat</th>
                                <th style="border-top-width: 1px" nowrap>Posting</th>
                                <th style="border-top-width: 1px" nowrap class="text-center">Aksi</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<footer id="footer" class="footer text-white" style="background-color: var(--color-default);">
    <div class="container">
        <div class="row gy-3">
            <div class="col-lg-4 col-md-4 d-flex">
                <i class="bi bi-geo-alt icon text-white"></i>
                <div class="address">
                    <h4 class="text-white">Alamat</h4>
                    <p>{{ $pengaturan->alamat }}</p>
                </div>

            </div>

            <div class="col-lg-5 col-md-5 d-flex">
                <i class="bi bi-telephone icon text-white"></i>
                <div>
                    <h4 class="text-white">Kontak</h4>
                    <p>
                        Admin Geoletter: <span>{{ $pengaturan->admin_geoletter }}</span><br>
                        Admin Peminjaman Ruangan: <span>{{ $pengaturan->admin_ruang }}</span><br>
                        Admin Peminjaman Alat Lab: <span>{{ $pengaturan->admin_peralatan }}</span>
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 d-flex">
                <i class="bi bi-person-plus icon text-white"></i>
                <div class="social-links">
                    <h4 class="text-white">Ikuti Kami</h4>
                    <div class="d-flex text-white">
                        <a href="{{ $pengaturan->link_yt }}" target="_blank" class="youtube text-white"><i class="bi bi-youtube text-white"></i></a>
                        <a href="{{ $pengaturan->link_fb }}" target="_blank" class="facebook text-white"><i class="bi bi-facebook"></i></a>
                        <a href="{{ $pengaturan->link_ig }}" target="_blank" class="instagram text-white"><i class="bi bi-instagram"></i></a>
                        <a href="{{ $pengaturan->link_linkedin }}" target="_blank" class="linkedin text-white"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="container copyright text-center text-white">
        <p>© <span>{{ date('Y') }} Copyright</span> <strong class="px-1 sitename">GeoReserve</strong> <span>All Rights Reserved</span></p>
    </div>

</footer>

<!-- Scroll Top -->
<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Preloader -->
<div id="preloader"></div>

<!-- Vendor JS Files -->
<script src="{{ asset('landing_page_rss/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('landing_page_rss/assets/vendor/php-email-form/validate.js') }}../../../public/"></script>
<script src="{{ asset('landing_page_rss/assets/vendor/aos/aos.js') }}"></script>
<script src="{{ asset('landing_page_rss/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
<script src="{{ asset('landing_page_rss/assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
<script src="{{ asset('landing_page_rss/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>

<!-- Main JS File -->
<script src="{{ asset('landing_page_rss/assets/js/main.js') }}"></script>

</body>

</html>
