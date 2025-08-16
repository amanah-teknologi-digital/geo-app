@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Route;
    use Laravolt\Avatar\Facade as Avatar;

    $avatar = Avatar::create(auth()->user()->name)->toBase64();
    $containerNav = $containerNav ?? 'container-fluid';
    $navbarDetached = ($navbarDetached ?? '');
@endphp

    <!-- Navbar -->
@if(isset($navbarDetached) && $navbarDetached == 'navbar-detached')
    <nav class="layout-navbar {{$containerNav}} navbar navbar-expand-xl {{$navbarDetached}} align-items-center bg-navbar-theme" id="layout-navbar">
        @endif
        @if(isset($navbarDetached) && $navbarDetached == '')
            <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
                <div class="{{$containerNav}}">
                    @endif

                    <!--  Brand demo (display only for navbar-full and hide on below xl) -->
                    @if(isset($navbarFull))
                        <div class="app-brand justify-content-center">
                            <a href="{{url('/')}}" class="logo d-flex align-items-center me-auto me-xl-0 order-first">
                                <img src="{{ asset('landing_page_rss/teknikgeo.png') }}" alt="">
                                <h1 class="sitename">GeoReserve</h1>
                            </a>
                        </div>

                    @endif

                    <!-- ! Not required for layout-without-menu -->
                    @if(!isset($navbarHideToggle))
                        <div
                            class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ?' d-xl-none ' : '' }}">
                            <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                                <i class="bx bx-menu bx-md"></i>
                            </a>
                        </div>
                    @endif

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <ul class="navbar-nav flex-row align-items-center justify-start items-start">
                            <li id="loader_notifikasi" style="display: none">
                                <div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
                            </li>
                            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2" id="kontent_notifikasi">
                                <a class="nav-link dropdown-toggle hide-arrow d-flex align-items-center" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    <span class="position-relative" id="icon_notifikasi">
                                        <i class="icon-base bx bx-bell icon-md"></i>
                                        <span class="badge rounded-pill bg-danger badge-dot badge-notifications border" id="tanda_notif" style="display: none"></span>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-start p-0" style="min-width: 25rem; !important;">
                                    <li class="dropdown-menu-header border-bottom">
                                        <div class="dropdown-header d-flex align-items-center py-3">
                                            <h6 class="mb-0 me-auto">Notifikasi</h6>
                                            <div class="d-flex align-items-center h6 mb-0">
                                                <span class="badge bg-label-primary me-2" id="pesan_notifikasi">tidak ada notifikasi</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="dropdown-notifications-list scrollable-container ps ps--active-y">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item list-group-item-action dropdown-notifications-item" id="data_notif_surat" style="display: none">
                                                <a href="{{ route('pengajuansurat') }}">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="avatar">
                                                                <i class="icon-base bx bxs-envelope" style="font-size: 2.5rem;"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-0">Pengajuan {{ (!empty(config('variables.namaLayananPersuratan')) ? config('variables.namaLayananPersuratan') : 'Persuratan') }}</h6>
                                                            <ul>
                                                                <li id="notif_surat_ajukan" style="display: none">
                                                                    <small class="mb-1 d-block text-body">Sebanyak&nbsp;<b class="text-danger" id="jml_surat_ajukan">0</b>&nbsp;pengajuan belum diajukan</small>
                                                                </li>
                                                                <li id="notif_surat_verifikasi" style="display: none">
                                                                    <small class="mb-1 d-block text-body">Sebanyak&nbsp;<b class="text-danger" id="jml_surat_verifikasi">0</b>&nbsp;pengajuan belum diverifikasi</small>
                                                                </li>
                                                                <li id="notif_surat_revisi" style="display: none">
                                                                    <small class="mb-1 d-block text-body">Sebanyak&nbsp;<b class="text-danger" id="jml_surat_revisi">0</b>&nbsp;pengajuan direvisi</small>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="dropdown-notifications-list scrollable-container ps ps--active-y">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item list-group-item-action dropdown-notifications-item" id="data_notif_ruangan" style="display: none">
                                                <a href="{{ route('pengajuanruangan') }}">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="avatar">
                                                                <i class="icon-base bx bxs-buildings" style="font-size: 2.5rem;"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-0">Pengajuan {{ (!empty(config('variables.namaLayananSewaRuangan')) ? config('variables.namaLayananSewaRuangan') : 'Ruangan') }}</h6>
                                                            <ul>
                                                                <li id="notif_ruangan_ajukan" style="display: none">
                                                                    <small class="mb-1 d-block text-body">Sebanyak&nbsp;<b class="text-danger" id="jml_ruangan_ajukan">0</b>&nbsp;pengajuan belum diajukan</small>
                                                                </li>
                                                                <li id="notif_ruangan_verifikasi" style="display: none">
                                                                    <small class="mb-1 d-block text-body">Sebanyak&nbsp;<b class="text-danger" id="jml_ruangan_verifikasi">0</b>&nbsp;pengajuan belum diverifikasi</small>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>

                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- User -->
                            <li class="nav-item lh-1 me-4">
                                <b>Akses: </b><span class="badge bg-label-success">{{ session('akses_default_nama')  }}</span>
                            </li>
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
                                   data-bs-toggle="dropdown">
                                    <div class="avatar">
                                        <img src="{{ $avatar }}" alt
                                             class="w-px-40 h-auto rounded-circle">
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar">
                                                        <img src="{{ $avatar }}" alt
                                                             class="w-px-40 h-auto rounded-circle">
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                                                    <small class="text-muted">{{ auth()->user()->kartu_id }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                            <i class="bx bx-user bx-md me-3"></i><span>Data Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#gantiAksesModal">
                                            <i class="bx bx-user-voice bx-md me-3"></i><span>Ganti Hak Akses</span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="/">
                                            <i class="bx bx-arrow-back bx-md me-3"></i><span>Kembali ke Landing Page</span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1"></div>
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                                <i class="text-danger bx bx-power-off bx-md me-3"></i><span>Log Out</span>
                                            </a>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>

                    @if(!isset($navbarDetached))
                </div>
                @endif
            </nav>
            <!-- / Navbar -->
@if(isset($navbarDetached) && $navbarDetached == 'navbar-detached')
    </nav>
@endif
