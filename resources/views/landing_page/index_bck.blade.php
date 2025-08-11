@php use Illuminate\Support\Facades\Storage; @endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>GeoReserve</title>
    <meta name="description" content="Sistem Informasi Manajemen Geofisika ITS(SIMADU) untuk pengajuan persuratan, booking ruangan, booking peralatan, dan informasi terbaru. Efisien, cepat, dan terintegrasi.">
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
        .col-bg {
            position: relative;
            background-image: url({{ asset('landing_page_rss/gedung.png') }});
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: white;
            padding: 50px;
        }

        /* Overlay semi-transparan */
        .col-bg::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Warna hitam dengan transparansi 50% */
        }

        /* Agar teks tetap terlihat di atas overlay */
        .col-bg > * {
            position: relative;
            z-index: 1;
        }

        .news-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }
        .news-card:hover {
            transform: translateY(-5px);
        }
        .news-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        .news-content {
            padding: 20px;
        }
        .news-date {
            background-color: #dc3545;
            color: white;
            text-align: center;
            padding: 3px;
            border-radius: 8px;
            position: absolute;
            bottom: 10px;
            right: 10px;
            font-size: 12px;
        }
        .icon-text {
            font-size: 14px;
            color: gray;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .news-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-top: 1px solid #eee;
        }
        .judulnews {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
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
                <li><a href="#hero" class="active"><i class="bi bi-house-door-fill"></i>&nbsp;Halaman Utama<br></a></li>
                <li><a href="#pengumuman"><i class="bi bi-newspaper"></i>&nbsp;Pengumuman</a></li>
                <li><a href="#layanan"><i class="bi bi-info-circle-fill"></i>&nbsp;Layanan Support</a></li>
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

<main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section col-bg">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row gy-12 justify-content-center justify-content-lg-between">
                <div class="col-lg-12 d-flex flex-column justify-content-center">
                    <h3 data-aos="fade-up" class="font-bold" style="color: var(--color-secondary);text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.8);">Sistem Informasi Manajemen Geofisika</h3>
                    <br>
                    <p data-aos="fade-up" data-aos-delay="100" class="text-white">Sistem ini menyediakan layanan persuratan, peminjaman ruangan,
                        <br>dan peminjaman alat di Departemen Teknik Geofisika ITS</p>
                </div>
            </div>
        </div>
    </section><!-- /Hero Section -->

    <!-- Pengumuman Section -->
    <section id="pengumuman" class="pengumuman section">
        <div class="container section-title" data-aos="fade-up">
            <h4 class="font-bold header-section" ><i class="bi bi-newspaper"></i>&nbsp;Pengumuman</h4>
        </div><!-- End Section Title -->

        <div class="container">
            <div class="row justify-content-center g-4">
            @if(count($pengumumanterbaru) > 0)
                    @foreach($pengumumanterbaru as $rows)
                        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                            <a href="{{ route('pengumuman.lihatpengumuman', $rows->id_pengumuman) }}">
                                <div class="news-card bg-white position-relative">
                                    @php
                                        $file = $rows->gambar_header;
                                        $filePath = $rows->file_pengumuman->location;
                                        $imageUrl = Storage::disk('public')->exists($filePath)
                                            ? route('file.getpublicfile', $file)
                                            : asset('assets/img/no_image.jpg');
                                    @endphp
                                    <img src="{{ $imageUrl }}" class="news-img" alt="{{ $rows->judul }}">
                                    <div class="news-content">
                                        <h5 class="judulnews">{{ $rows->judul }}</h5>
                                        <p style="max-height: 10vh;min-height: 10vh">{!! Str::limit(strip_tags($rows->data), 100, '...') !!}</p>
    {{--                                    <span class="mt-3 mb-3 text-black"><i><a href="#" class="badge bg-info">Lihat selengkapnya <i class="bi bi-arrow-right"></i></a></i></span>--}}
                                    </div>
                                    <div class="news-footer">
                                        <div class="icon-text">
                                            <span>👤 {{ $rows->postinger_user->name }}</span>
                                        </div>
                                    </div>
                                    <div class="news-date">{{ $rows->tgl_posting->format('d/m/Y') }}</div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="alert alert-warning" >Belum ada pengumuman terkini!</div>
                    </div>
                @endif
            </div>
        </div>
        @if(count($pengumumanterbaru) > 0)
            <div class="text-center pt-5" data-aos="fade-up" data-aos-delay="100">
                <a href="{{ route('pengumuman.listpengumuman') }}" class="more-btn animated"><span>Lihat Semua Pengumuman</span> <i class="bi bi-chevron-right"></i></a>
            </div>
        @endif
    </section>

    <!-- Tentang Section -->
    <section id="layanan" class="why-us section light-background">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="why-box" style="background-color: var(--color-default)">
                        <h3>Layanan Support</h3>
                        <p>
                            Ada beberapa Layanan yang ada pada Sistem ini, yaitu: <br>
                            <i class="bi bi-check-circle text-info"></i> <b>Geo Letter</b>: Melayani Persuratan dan perizinan<br>
                            <i class="bi bi-check-circle text-info"></i> <b>Geo Room</b>: Perizinan dan Penyewaan Ruangan<br>
                            <i class="bi bi-check-circle text-info"></i> <b>Geo Facility</b>: Perizinan dan Penyewaan Alat/Lab<br>
                            <br>
                            <i>untuk menggunakan layanan ini pengguna diharapkan untuk login pada halaman yang disediakan.</i>
                        </p>
                        <div class="text-center">
                            <a href="#" class="more-btn"><span>Lihat Selengkapnya</span> <i class="bi bi-chevron-right"></i></a>
                        </div>
                    </div>
                </div><!-- End Why Box -->

                <div class="col-lg-8 d-flex align-items-stretch">
                    <div class="row gy-4" data-aos="fade-up" data-aos-delay="200">

                        <div class="col-xl-4">
                            <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                                <i class="bi bi-clipboard-data"></i>
                                <h4>Geo Letter</h4>
                                <p>Layanan ini mengarah pada perizinan persuratan, dengan mekanisme mendowload dan mengupload sesuai template surat kemudian mengunduhnya jika sudah disetujui admin.</p>
                                <br>
                                @php
                                    $filePath = optional($pengaturan->files_geoletter)->location ?? 'no-exist';
                                    $fileId = optional($pengaturan->files_geoletter)->id_file ?? -1;
                                    $imageUrl = Storage::disk('public')->exists($filePath)
                                        ? route('file.getpublicfile', $fileId)
                                        : 'javascript:void(0)';
                                @endphp
                                <div class="text-center">
                                    <a href="{{ $imageUrl }}" target="_blank" class="btn btn-sm btn-outline-primary"><span>Lihat SOP</span></a>
                                </div>
                            </div>
                        </div><!-- End Icon Box -->

                        <div class="col-xl-4" data-aos="fade-up" data-aos-delay="300">
                            <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                                <i class="bi bi-hospital-fill"></i>
                                <h4>Geo Room</h4>
                                <p>Layanan ini memberikan pembokingan ruangan, dengan ketentuan melakukan pengajuan 2 hari sebelum pemakaian ruangan dan melampirkan berita acara setelah disetujui.</p>
                                <br>
                                @php
                                    $filePath = optional($pengaturan->files_georoom)->location ?? 'no-exist';
                                    $fileId = optional($pengaturan->files_georoom)->id_file ?? -1;
                                    $imageUrl = Storage::disk('public')->exists($filePath)
                                        ? route('file.getpublicfile', $fileId)
                                        : 'javascript:void(0)';
                                @endphp
                                <div class="text-center">
                                    <a href="{{ $imageUrl }}" target="_blank" class="btn btn-sm btn-outline-primary"><span>Lihat SOP</span></a>
                                </div>
                            </div>
                        </div><!-- End Icon Box -->

                        <div class="col-xl-4" data-aos="fade-up" data-aos-delay="400">
                            <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                                <i class="bi bi-gear-fill"></i>
                                <h4>Geo Facility</h4>
                                <p>Layanan untuk memanajemen peminjaman alat/lab, dengan vasiasi stok dan harga sesuai dengan durasi peminjaman alat. ada cost tambahan jika ada kerusakan.</p>
                                <br>
                                @php
                                    $filePath = optional($pengaturan->files_geofacility)->location ?? 'no-exist';
                                    $fileId = optional($pengaturan->files_geofacility)->id_file ?? -1;
                                    $imageUrl = Storage::disk('public')->exists($filePath)
                                        ? route('file.getpublicfile', $fileId)
                                        : 'javascript:void(0)';
                                @endphp
                                <div class="text-center">
                                    <a href="{{ $imageUrl }}" target="_blank" class="btn btn-sm btn-outline-primary" ><span>Lihat SOP</span></a>
                                </div>
                            </div>
                        </div><!-- End Icon Box -->
                    </div>
                </div>
            </div>
        </div>
    </section><!-- /Why Us Section -->
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
