@php use Illuminate\Support\Facades\Storage; @endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>GeoReserve</title>
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
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-success me-2 rounded-3"><i class="bi bi-house"></i>&nbsp;Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-sm btn-nav-masuk me-2"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-sm btn-nav-daftar"><i class="bi bi-person-plus-fill me-1"></i> Daftar</a>
            @endif
        </div>
    </div>
</nav>

<!-- Hero Section -->
<header id="hero" class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 text-lg-start text-center" >
                <h1 class="sitename" data-aos="fade-right" data-aos-delay="0">Selamat Datang di GeoReserve</h1>
                <p data-aos="fade-right" data-aos-delay="100">Kelola persuratan, booking ruangan dan peralatan, serta pantau pengumuman terbaru dalam satu platform yang cepat, aman, dan terintegrasi.</p>
                <div class="hero-buttons d-flex flex-wrap gap-2 justify-content-center justify-content-lg-start" data-aos="fade-right" data-aos-delay="200">
                    <a href="{{ route('pengajuansurat') }}" class="btn btn-ajukan"><i class="bi bi-envelope-fill"></i>Ajukan Surat</a>
                    <a href="{{ route('pengajuanruangan') }}" class="btn btn-outline-primary"><i class="bi bi-calendar2-check-fill"></i>Booking Ruangan</a>
                    <a href="{{ route('pengajuanperalatan') }}" class="btn btn-outline-primary"><i class="bi bi-projector-fill"></i>Booking Peralatan</a>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="hero-carousel-container">
                    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{{ asset('landing_page_rss/gedung.png') }}" class="d-block w-100" alt="Students collaborating">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('landing_page_rss/geoletter_img.png') }}" class="d-block w-100" alt="Using a system">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('landing_page_rss/room_img.png') }}" class="d-block w-100" alt="Team working">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
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
                        <h2 class="content-title">GeoReserve</h2>
                        <p class="text-muted">GeoReserve (Sistem Informasi Manajemen Geofisika) adalah platform digital yang dirancang untuk merevolusi cara kita mengelola administrasi dan fasilitas. Tujuan kami adalah menyederhanakan proses yang kompleks, mengurangi birokrasi, dan menyediakan akses informasi yang cepat dan transparan bagi seluruh civitas akademika.</p>

                        <hr class="my-4">

                        <h4 class="mb-4">Layanan Utama Kami</h4>
                        <div class="row g-4 layanan-utama-item mb-3">
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
                            @if(count($pengumumanterbaru) > 0)
                                @foreach($pengumumanterbaru as $rows)
                                    @php
                                        $file = $rows->gambar_header;
                                        $filePath = $rows->file_pengumuman->location;
                                        $imageUrl = Storage::disk('public')->exists($filePath)
                                            ? route('file.getpublicfile', $file)
                                            : asset('assets/img/no_image.jpg');
                                    @endphp
                                    <div class="pengumuman-item">
                                        <img src="{{ $imageUrl }}" class="me-3 rounded" alt="{{ $rows->judul }}" loading="lazy">
                                        <div>
                                            <p class="pengumuman-meta">{{ $rows->tgl_posting->format('d/m/Y') }}</p>
                                            <a href="{{ route('pengumuman.lihatpengumuman', $rows->id_pengumuman) }}" class="pengumuman-title d-block">{{ $rows->judul }}</a>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-warning" >Belum ada pengumuman terkini!</div>
                            @endif
                        </div>
                    </div>

                    <!-- Jadwal Ruangan Tersedia -->
                    <div class="card-custom mb-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="card-header-custom"><i class="bi bi-calendar-week-fill me-2"></i>Ruangan Tersedia</div>
                        <div class="card-body p-4">
                            @if(count($ruangantersedia) > 0)
                                @foreach($ruangantersedia as $rows)
                                    @php
                                        $file = $rows->gambar_file;
                                        $filePath = $rows->gambar->location;
                                        $imageUrl = Storage::disk('public')->exists($filePath)
                                            ? route('file.getpublicfile', $file)
                                            : asset('assets/img/no_image.jpg');
                                    @endphp

                                    <div class="sidebar-item">
                                        <img src="{{ $imageUrl }}" alt="{{ $rows->nama }}">
                                        <div class="sidebar-item-details">
                                            <h6>{{ $rows->nama }}</h6>
                                            <span class="badge badge-primary small">{{ $rows->jenis_ruangan }}</span>
                                            <div>
                                                <small class="text-muted">Kapasitas: {{ $rows->kapasitas }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-warning" >Ruangan tersedia kosong!</div>
                            @endif
                            @if(count($ruangantersedia) > 0)
                                <div class="d-grid mt-4">
                                    <a href="{{ route('listruangan') }}" class="btn btn-primary rounded-pill btn-sm btn-lihat-semua">Lihat Semua Ruangan <i class="bi bi-arrow-right-short"></i></a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Peralatan Tersedia -->
                    <div class="card-custom" data-aos="fade-up" data-aos-delay="300">
                        <div class="card-header-custom"><i class="bi bi-display-fill me-2"></i>Peralatan Tersedia</div>
                        <div class="card-body p-4">
                            <div class="sidebar-item">
                                <img src="{{ asset('landing_page_rss/assets/img/events-1.jpg') }}" alt="Proyektor">
                                <div class="sidebar-item-details">
                                    <h6>Proyektor InFocus</h6>
                                </div>
                            </div>
                            <div class="sidebar-item">
                                <img src="{{ asset('landing_page_rss/assets/img/events-2.jpg') }}" alt="Sound System">
                                <div class="sidebar-item-details">
                                    <h6>Sound System Portable</h6>
                                </div>
                            </div>
                            <div class="sidebar-item">
                                <img src="{{ asset('landing_page_rss/assets/img/events-3.jpg') }}" alt="Webcam">
                                <div class="sidebar-item-details">
                                    <h6>Webcam Logitech</h6>
                                </div>
                            </div>
                            <div class="d-grid mt-4">
                                <a href="{{ route('listperalatan') }}" class="btn btn-primary rounded-pill btn-sm btn-lihat-semua">Lihat Semua Alat <i class="bi bi-arrow-right-short"></i></a>
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
                        <p class="mb-2"><strong>Admin Persuratan:</strong> {{ $pengaturan->admin_geoletter }}</p>
                        <p class="mb-2"><strong>Admin Ruangan:</strong> {{ $pengaturan->admin_ruang }}</p>
                        <p class="mb-0"><strong>Admin Peralatan:</strong> {{ $pengaturan->admin_peralatan }}</p>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="map-container">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3927.2928733842655!2d112.79700709250486!3d-7.279900165259386!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xeb3150bedb5cd2f2!2sTeknik+Geofisika+FTSP+ITS!5e0!3m2!1sid!2sid!4v1539787165326" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
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
