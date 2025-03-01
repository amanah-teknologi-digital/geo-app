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
    <nav
        class="layout-navbar {{$containerNav}} navbar navbar-expand-xl {{$navbarDetached}} align-items-center bg-navbar-theme"
        id="layout-navbar">
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
                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- User -->
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
                                                    <small class="text-muted">{{ auth()->user()->akses->nama }}</small>
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
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                                <i class="bx bx-power-off bx-md me-3"></i><span>Log Out</span>
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
