<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO Meta Tags -->
    <title>SIMADU - Sistem Informasi Manajemen Terpadu</title>
    <meta name="description" content="Sistem Informasi Manajemen Terpadu (SIMADU) untuk pengajuan persuratan, booking ruangan, booking peralatan, dan informasi terbaru. Efisien, cepat, dan terintegrasi.">
    <meta name="keywords" content="sistem informasi, manajemen, persuratan, booking ruangan, booking peralatan, simadu">
    <meta name="author" content="Nama Institusi Anda">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts (Poppins) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- AOS (Animate On Scroll) CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4A69A5; /* Warna biru yang lebih lembut */
            --primary-hover: #3E5A8E;
            --secondary-color: #f8f9fa;
            --text-color: #343a40;
            --light-gray: #e9ecef;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 70px; /* Offset for fixed navbar */
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--secondary-color);
            color: var(--text-color);
            overflow-x: hidden; /* Mencegah horizontal scroll */
        }

        /* Navbar */
        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            background-color: #fff;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--text-color) !important;
        }

        .nav-link {
            font-weight: 500;
            color: var(--text-color);
            transition: color 0.3s;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary-color);
            font-weight: 600;
        }

        .btn-nav-daftar {
            background-color: var(--primary-color);
            border: 1px solid var(--primary-color);
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 8px;
        }
        .btn-nav-daftar:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            color: white;
        }
        .btn-nav-masuk {
            background-color: #fff;
            border: 1px solid var(--light-gray);
            color: var(--text-color);
            padding: 0.4rem 1rem;
            border-radius: 8px;
        }
        .btn-nav-masuk:hover {
            background-color: var(--secondary-color);
        }


        /* Hero Section */
        .hero-section {
            background: radial-gradient(ellipse at center, #E0E8F5 0%, #FFFFFF 70%);
            padding: 100px 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .hero-section h1 {
            font-weight: 700;
            font-size: 3.0rem; /* Ukuran judul dikecilkan */
            color: var(--text-color);
            line-height: 1.2;
        }

        .hero-section p {
            font-size: 1.1rem;
            color: #6c757d;
            max-width: 500px;
            margin-bottom: 30px;
        }

        .hero-buttons .btn {
            padding: 12px 24px;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease;
            flex-shrink: 0; /* Mencegah tombol menyusut */
        }

        .hero-buttons .btn .bi {
            margin-right: 8px;
        }

        .hero-buttons .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(74, 105, 165, 0.2);
        }

        .hero-buttons .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: #fff;
        }
        .hero-buttons .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .hero-buttons .btn-outline-primary {
            color: var(--text-color);
            border-color: var(--light-gray);
            background-color: #fff;
        }
        .hero-buttons .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: #fff;
            border-color: var(--primary-color);
        }

        .hero-image {
            max-width: 100%;
            height: auto;
        }

        /* Responsive Typography & Buttons */
        @media (max-width: 992px) {
            .hero-section .text-lg-start {
                text-align: center !important;
            }
        }
        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2.5rem;
            }
            .hero-section {
                padding: 80px 0;
            }
        }
        @media (max-width: 576px) {
            .hero-buttons .btn {
                padding: 10px 16px;
                font-size: 0.9rem;
            }
        }


        /* Main Content Area */
        .main-content {
            background-color: #fff;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .content-title {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .layanan-utama-item .icon-box {
            font-size: 2rem;
            color: var(--primary-color);
            background-color: #e7f0ff;
            width: 60px;
            height: 60px;
            line-height: 60px;
            text-align: center;
            border-radius: 50%;
        }

        .layanan-persuratan-card {
            background-color: #fff;
            border: 1px solid var(--light-gray);
            border-radius: 10px;
            padding: 1.25rem;
            text-align: center;
            height: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }
        .layanan-persuratan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(74, 105, 165, 0.1);
        }
        .layanan-persuratan-card .fs-2 {
            margin-bottom: 0.75rem;
            color: var(--primary-color);
        }
        .layanan-persuratan-card h6 {
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Sidebar Card */
        .card-custom {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            background-color: #fff;
        }

        .card-header-custom {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 0.8rem 1.25rem;
            font-size: 0.95rem;
        }

        .pengumuman-item {
            padding: 1rem 0;
            border-bottom: 1px solid var(--light-gray);
        }
        .pengumuman-item:first-child {
            padding-top: 0;
        }
        .pengumuman-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .pengumuman-meta {
            font-size: 0.8rem;
            color: #6c757d;
            margin-bottom: 0.25rem;
        }
        .pengumuman-title {
            font-weight: 600;
            color: var(--text-color);
            text-decoration: none;
            transition: color 0.3s;
            font-size: 0.9rem;
        }
        .pengumuman-title:hover {
            color: var(--primary-color);
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid var(--light-gray);
        }
        .sidebar-item:last-of-type {
            border-bottom: none;
            padding-bottom: 0.75rem;
        }
        .sidebar-item img {
            width: 70px;
            height: 55px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
        }
        .sidebar-item-details {
            flex-grow: 1;
        }
        .sidebar-item-details h6 {
            font-weight: 600;
            margin-bottom: 4px;
            font-size: 0.9rem;
        }
        .sidebar-item-details .badge {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.25em 0.5em;
            background-color: #e7f0ff !important;
            color: var(--primary-color) !important;
        }
        .sidebar-item-details small {
            font-size: 0.8rem;
        }

        .btn-lihat-semua {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .btn-lihat-semua:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(74, 105, 165, 0.3);
        }

        /* Alur Proses Section */
        .alur-proses-section {
            padding: 4rem 0;
            background: radial-gradient(ellipse at center, #E0E8F5 0%, #FFFFFF 70%);
        }
        .step-card {
            text-align: center;
            padding: 2rem;
        }
        .step-icon {
            width: 80px;
            height: 80px;
            background-color: #fff;
            color: var(--primary-color);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        .step-card h5 {
            font-weight: 600;
            color: var(--text-color);
        }
        .step-card p {
            font-size: 0.9rem;
            color: #6c757d;
        }

        /* FAQ Section */
        .faq-section .accordion-button {
            font-weight: 600;
            color: var(--text-color);
        }
        .faq-section .accordion-button:not(.collapsed) {
            background-color: #e7f0ff;
            color: var(--primary-color);
            box-shadow: none;
        }
        .faq-section .accordion-button:focus {
            box-shadow: 0 0 0 0.25rem rgba(74, 105, 165, 0.25);
        }
        .faq-section .accordion-item {
            border-radius: 15px !important;
            overflow: hidden;
            border: 1px solid var(--light-gray);
        }

        /* Kontak Section */
        #kontak .map-container {
            border-radius: 15px;
            overflow: hidden;
            height: 100%;
            min-height: 300px;
        }
        #kontak .contact-details {
            background-color: var(--primary-color);
            color: #fff;
            padding: 2rem;
            border-radius: 15px;
        }
        #kontak .contact-details h5 {
            font-weight: 600;
        }
        #kontak .contact-details p {
            color: rgba(255,255,255,0.9);
            margin-bottom: 1.5rem;
        }

        /* Footer */
        .footer {
            background-color: var(--primary-color);
            color: #fff;
            padding: 60px 0 20px 0;
        }
        .footer-title {
            font-weight: 600;
            margin-bottom: 20px;
        }
        .footer-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            transition: color 0.3s;
            font-size: 0.95rem;
        }
        .footer-links a:hover {
            color: white;
        }
        .footer-links .bi {
            margin-right: 8px;
            font-size: 0.8rem;
        }
        .footer-social a {
            color: white;
            font-size: 1.5rem;
            margin-right: 15px;
            transition: transform 0.3s;
            display: inline-block;
        }
        .footer-social a:hover {
            transform: scale(1.1);
        }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.2);
            padding-top: 20px;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">
            SIM Terpadu
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#hero">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#main-section">Layanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#alur-proses">Alur Proses</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#faq">FAQ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#kontak">Kontak</a>
                </li>
            </ul>
            <a href="#" class="btn btn-nav-masuk me-2"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk</a>
            <a href="#" class="btn btn-nav-daftar"><i class="bi bi-person-plus-fill me-1"></i> Daftar</a>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<header id="hero" class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 text-lg-start text-center">
                <h1 data-aos="fade-right">Sistem Informasi Manajemen Terpadu</h1>
                <p data-aos="fade-right" data-aos-delay="100">Kelola persuratan, booking ruangan dan peralatan, serta pantau pengumuman terbaru dalam satu platform yang cepat, aman, dan terintegrasi.</p>
                <div class="hero-buttons d-flex flex-wrap gap-2 justify-content-center justify-content-lg-start" data-aos="fade-right" data-aos-delay="200">
                    <a href="#" class="btn btn-primary"><i class="bi bi-envelope-fill"></i>Ajukan Surat</a>
                    <a href="#" class="btn btn-outline-primary"><i class="bi bi-calendar2-check-fill"></i>Booking Ruangan</a>
                    <a href="#" class="btn btn-outline-primary"><i class="bi bi-projector-fill"></i>Booking Peralatan</a>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <img src="https://storage.googleapis.com/proudcity/mebanenc/uploads/2021/03/undraw_data_processing_yrrv.svg" alt="Ilustrasi Sistem Informasi" class="hero-image" data-aos="fade-left">
            </div>
        </div>
    </div>
</header>

<main>
    <!-- Main Content & Sidebar Section -->
    <section id="main-section" class="py-5">
        <div class="container mt-4">
            <div class="row g-5">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <div class="main-content h-100" data-aos="fade-up">
                        <h2 class="content-title">Selamat Datang di SIMADU</h2>
                        <p class="text-muted">SIMADU (Sistem Informasi Manajemen Terpadu) adalah platform digital yang dirancang untuk merevolusi cara kita mengelola administrasi dan fasilitas. Tujuan kami adalah menyederhanakan proses yang kompleks, mengurangi birokrasi, dan menyediakan akses informasi yang cepat dan transparan bagi seluruh civitas akademika.</p>

                        <hr class="my-4">

                        <h4 class="mb-4">Layanan Utama Kami</h4>
                        <div class="row g-4 layanan-utama-item">
                            <div class="col-12 d-flex mb-3">
                                <div class="icon-box me-3 flex-shrink-0"><i class="bi bi-envelope-paper-fill"></i></div>
                                <div>
                                    <h6 class="fw-bold">Pengajuan Persuratan</h6>
                                    <p class="text-muted small mb-0">Proses pengajuan berbagai surat keperluan akademik dan administrasi menjadi lebih cepat dan mudah. Lupakan antrean panjang dan proses manual yang memakan waktu.</p>
                                </div>
                            </div>
                            <div class="col-12 d-flex mb-3">
                                <div class="icon-box me-3 flex-shrink-0"><i class="bi bi-calendar2-check-fill"></i></div>
                                <div>
                                    <h6 class="fw-bold">Booking Ruangan</h6>
                                    <p class="text-muted small mb-0">Butuh ruang untuk rapat, diskusi, atau kegiatan lainnya? Cek ketersediaan, lihat fasilitas, dan lakukan pemesanan langsung dari mana saja.</p>
                                </div>
                            </div>
                            <div class="col-12 d-flex">
                                <div class="icon-box me-3 flex-shrink-0"><i class="bi bi-projector-fill"></i></div>
                                <div>
                                    <h6 class="fw-bold">Peminjaman Peralatan</h6>
                                    <p class="text-muted small mb-0">Pinjam proyektor, sound system, dan inventaris lainnya dengan mudah. Pastikan semua kebutuhan teknis kegiatan Anda terpenuhi.</p>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h4 class="mb-4">Jenis Surat yang Dilayani</h4>
                        <div class="row g-3">
                            <div class="col-md-6 col-lg-4 mb-3" data-aos="fade-up" data-aos-delay="50">
                                <div class="layanan-persuratan-card">
                                    <i class="bi bi-briefcase-fill fs-2"></i>
                                    <h6>Surat Permohonan Magang/KP</h6>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3" data-aos="fade-up" data-aos-delay="100">
                                <div class="layanan-persuratan-card">
                                    <i class="bi bi-patch-check-fill fs-2"></i>
                                    <h6>Surat Keterangan Yudisium</h6>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3" data-aos="fade-up" data-aos-delay="150">
                                <div class="layanan-persuratan-card">
                                    <i class="bi bi-search fs-2"></i>
                                    <h6>Surat Permohonan Penelitian</h6>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3" data-aos="fade-up" data-aos-delay="200">
                                <div class="layanan-persuratan-card">
                                    <i class="bi bi-person-check-fill fs-2"></i>
                                    <h6>Surat Rekomendasi</h6>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3" data-aos="fade-up" data-aos-delay="250">
                                <div class="layanan-persuratan-card">
                                    <i class="bi bi-calendar2-week-fill fs-2"></i>
                                    <h6>Surat Ijin Perkuliahan</h6>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3" data-aos="fade-up" data-aos-delay="300">
                                <div class="layanan-persuratan-card">
                                    <i class="bi bi-book-half fs-2"></i>
                                    <h6>Surat Permohonan TA</h6>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3" data-aos="fade-up" data-aos-delay="350">
                                <div class="layanan-persuratan-card">
                                    <i class="bi bi-cash-coin fs-2"></i>
                                    <h6>Surat Keringanan UKT</h6>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3" data-aos="fade-up" data-aos-delay="400">
                                <div class="layanan-persuratan-card">
                                    <i class="bi bi-file-earmark-plus-fill fs-2"></i>
                                    <h6>Layanan Surat Lainnya</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4" id="informasi-sidebar">
                    <!-- Pengumuman -->
                    <div class="card-custom mb-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="card-header-custom"><i class="bi bi-megaphone-fill me-2"></i>Pengumuman Terbaru</div>
                        <div class="card-body p-4">
                            <div class="pengumuman-item">
                                <p class="pengumuman-meta"><i class="bi bi-calendar-event"></i> 10 Agustus 2025</p>
                                <a href="#" class="pengumuman-title d-block">Jadwal Maintenance Sistem SIMADU</a>
                            </div>
                            <div class="pengumuman-item">
                                <p class="pengumuman-meta"><i class="bi bi-calendar-event"></i> 08 Agustus 2025</p>
                                <a href="#" class="pengumuman-title d-block">Prosedur Baru Pengajuan Surat Rekomendasi</a>
                            </div>
                            <div class="pengumuman-item">
                                <p class="pengumuman-meta"><i class="bi bi-calendar-event"></i> 05 Agustus 2025</p>
                                <a href="#" class="pengumuman-title d-block">Peresmian Ruang Rapat Cendrawasih</a>
                            </div>
                        </div>
                    </div>

                    <!-- Jadwal Ruangan Tersedia -->
                    <div class="card-custom mb-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="card-header-custom"><i class="bi bi-calendar-week-fill me-2"></i>Ruangan Tersedia</div>
                        <div class="card-body p-4">
                            <div class="sidebar-item">
                                <img src="https://images.unsplash.com/photo-1596048133344-3c35b365e6c7?q=80&w=200&auto=format&fit=crop" alt="Aula">
                                <div class="sidebar-item-details">
                                    <h6>Aula Serbaguna</h6>
                                    <div>
                                        <span class="badge bg-light text-dark border me-2">A-01</span>
                                        <small class="text-muted">Kapasitas: 150 orang</small>
                                    </div>
                                </div>
                            </div>
                            <div class="sidebar-item">
                                <img src="https://images.unsplash.com/photo-1556761175-5973dc0f32e7?q=80&w=200&auto=format&fit=crop" alt="Ruang Rapat">
                                <div class="sidebar-item-details">
                                    <h6>Ruang Rapat Merak</h6>
                                    <div>
                                        <span class="badge bg-light text-dark border me-2">R-03</span>
                                        <small class="text-muted">Kapasitas: 25 orang</small>
                                    </div>
                                </div>
                            </div>
                            <div class="sidebar-item">
                                <img src="https://images.unsplash.com/photo-1560420025-9453f02b4724?q=80&w=200&auto=format&fit=crop" alt="Ruang Diskusi">
                                <div class="sidebar-item-details">
                                    <h6>Ruang Diskusi Elang</h6>
                                    <div>
                                        <span class="badge bg-light text-dark border me-2">D-05</span>
                                        <small class="text-muted">Kapasitas: 10 orang</small>
                                    </div>
                                </div>
                            </div>
                            <div class="sidebar-item">
                                <img src="https://images.unsplash.com/photo-1589998059171-988d887df646?q=80&w=200&auto=format&fit=crop" alt="Perpustakaan">
                                <div class="sidebar-item-details">
                                    <h6>Ruang Baca Perpustakaan</h6>
                                    <div>
                                        <span class="badge bg-light text-dark border me-2">P-01</span>
                                        <small class="text-muted">Kapasitas: 50 orang</small>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid mt-4">
                                <a href="#" class="btn btn-primary rounded-pill btn-sm btn-lihat-semua">Lihat Semua Ruangan <i class="bi bi-arrow-right-short"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Peralatan Tersedia -->
                    <div class="card-custom" data-aos="fade-up" data-aos-delay="300">
                        <div class="card-header-custom"><i class="bi bi-display-fill me-2"></i>Peralatan Tersedia</div>
                        <div class="card-body p-4">
                            <div class="sidebar-item">
                                <img src="https://images.unsplash.com/photo-1593030103057-24b41d514a9e?q=80&w=200&auto=format&fit=crop" alt="Proyektor">
                                <div class="sidebar-item-details">
                                    <h6>Proyektor InFocus</h6>
                                </div>
                            </div>
                            <div class="sidebar-item">
                                <img src="https://images.unsplash.com/photo-1589221469434-5d8491a58334?q=80&w=200&auto=format&fit=crop" alt="Sound System">
                                <div class="sidebar-item-details">
                                    <h6>Sound System Portable</h6>
                                </div>
                            </div>
                            <div class="sidebar-item">
                                <img src="https://images.unsplash.com/photo-1616421233882-12e0da42a55a?q=80&w=200&auto=format&fit=crop" alt="Webcam">
                                <div class="sidebar-item-details">
                                    <h6>Webcam Logitech</h6>
                                </div>
                            </div>
                            <div class="d-grid mt-4">
                                <a href="#" class="btn btn-primary rounded-pill btn-sm btn-lihat-semua">Lihat Semua Alat <i class="bi bi-arrow-right-short"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Alur Proses Section -->
    <section class="alur-proses-section" id="alur-proses">
        <div class="container">
            <h2 class="content-title text-center mb-5" data-aos="fade-up">Alur Proses Layanan yang Mudah</h2>
            <div class="row">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="step-card">
                        <div class="step-icon"><i class="bi bi-mouse"></i></div>
                        <h5>Langkah 1: Pilih Layanan</h5>
                        <p>Pilih salah satu layanan utama kami, seperti pengajuan surat, booking ruangan, atau peminjaman alat.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="step-card">
                        <div class="step-icon"><i class="bi bi-input-cursor-text"></i></div>
                        <h5>Langkah 2: Isi Formulir</h5>
                        <p>Lengkapi formulir online yang tersedia dengan data yang benar dan unggah dokumen pendukung jika diperlukan.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="step-card">
                        <div class="step-icon"><i class="bi bi-stopwatch"></i></div>
                        <h5>Langkah 3: Lacak Proses</h5>
                        <p>Pantau status pengajuan Anda secara real-time melalui sistem hingga mendapatkan konfirmasi.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-5">
        <div class="container">
            <h2 class="content-title text-center mb-5" data-aos="fade-up">Pertanyaan yang Sering Diajukan (FAQ)</h2>
            <div class="row justify-content-center">
                <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    Bagaimana cara mengajukan surat?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Anda dapat mengajukan surat dengan masuk ke akun Anda, memilih jenis surat yang diinginkan pada bagian layanan persuratan, mengisi formulir yang tersedia, dan mengunggah dokumen pendukung jika diperlukan.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Berapa lama proses pengajuan surat hingga selesai?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Waktu proses bervariasi tergantung jenis surat dan kelengkapan data. Namun, Anda dapat melacak status pengajuan secara real-time melalui dasbor akun Anda.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Bagaimana cara melihat status ruangan yang tersedia?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Status ketersediaan ruangan dapat dilihat langsung pada bagian "Ruangan Tersedia" di halaman ini atau melalui menu booking ruangan setelah Anda masuk ke sistem.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kontak Section -->
    <section id="kontak" class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-6" data-aos="fade-up">
                    <div class="contact-details h-100">
                        <h5><i class="bi bi-telephone-fill me-2"></i>Hubungi Kami</h5>
                        <p>Jika Anda memiliki pertanyaan atau memerlukan bantuan, jangan ragu untuk menghubungi admin kami.</p>
                        <hr class="border-light">
                        <p class="mb-2"><strong>Admin Persuratan:</strong> Dihein (081357760223)</p>
                        <p class="mb-2"><strong>Admin Ruangan:</strong> Sholichan (082233991889)</p>
                        <p class="mb-0"><strong>Admin Peralatan:</strong> Hamzah (08970007296)</p>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="map-container">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.641686539119!2d112.7916983147749!3d-7.281332994743736!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fa18a9313693%3A0x1e35d5e5c3c8f63!2sInstitut%20Teknologi%20Sepuluh%20Nopember!5e0!3m2!1sid!2sid!4v1694088313001!5m2!1sid!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <h5 class="footer-title">SIM Terpadu</h5>
                <p>Sistem Informasi Manajemen Terpadu yang membantu mengelola administrasi dan inventaris secara efisien dan transparan.</p>
                <div class="footer-social mt-4">
                    <a href="#"><i class="bi bi-twitter-x"></i></a>
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                <h5 class="footer-title">Navigasi</h5>
                <div class="footer-links">
                    <a href="#main-section"><i class="bi bi-dot"></i>Tentang Sistem</a>
                    <a href="#"><i class="bi bi-dot"></i>Layanan</a>
                    <a href="#"><i class="bi bi-dot"></i>Bantuan</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h5 class="footer-title">Layanan</h5>
                <div class="footer-links">
                    <a href="#"><i class="bi bi-dot"></i>Pengajuan Surat</a>
                    <a href="#"><i class="bi bi-dot"></i>Booking Ruangan</a>
                    <a href="#"><i class="bi bi-dot"></i>Booking Peralatan</a>
                    <a href="#"><i class="bi bi-dot"></i>Lacak Status</a>
                </div>
            </div>
            <div class="col-lg-3">
                <h5 class="footer-title">Alamat Kantor</h5>
                <ul class="list-unstyled">
                    <li class="mb-2 d-flex"><i class="bi bi-geo-alt-fill me-2 mt-1"></i><span>Jl. Teknologi No. 1, Surabaya, Indonesia</span></li>
                    <li class="mb-2 d-flex"><i class="bi bi-envelope-fill me-2 mt-1"></i><span>kontak@simadu.id</span></li>
                    <li class="mb-2 d-flex"><i class="bi bi-telephone-fill me-2 mt-1"></i><span>(031) 123-4567</span></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom text-center">
            <p class="mb-0">&copy; 2025 SIMADU - Sistem Informasi Manajemen Terpadu. All Rights Reserved.</p>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<!-- AOS JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800, // values from 0 to 3000, with step 50ms
        once: true, // whether animation should happen only once - while scrolling down
    });

    document.addEventListener('DOMContentLoaded', () => {
        // Smooth scroll for nav links
        document.querySelectorAll('.navbar .nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    targetElement.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Navbar active state on scroll
        const navLinks = document.querySelectorAll('.nav-link');
        const sections = document.querySelectorAll('header[id], section[id]');

        function activateMenu() {
            let current = 'hero'; // Default to hero
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= sectionTop - 75) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href').includes(current)) {
                    link.classList.add('active');
                }
            });
        }

        window.addEventListener('scroll', activateMenu);
        activateMenu(); // Call on page load
    });
</script>
</body>
</html>
