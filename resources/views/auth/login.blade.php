@extends('layouts/blankLayout')

@section('title', 'Login'.' • '.config('variables.templateName'))

@section('page-style')
    @vite([
      'resources/assets/vendor/scss/pages/page-auth.scss',
      'resources/assets/css/custom.scss'
    ])
@endsection

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card px-sm-6 px-0">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="{{url('/')}}" class="logo d-flex align-items-center me-auto me-xl-0 order-first">
                                <img src="{{ asset('landing_page_rss/teknikgeo.png') }}" alt="">
                                <h1 class="sitename">GeoReserve</h1>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <p class="mb-6">Silahkan login ke akunmu dan mulai menikmati layanan {{config('variables.templateName')}} 👋</p>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="formAuthentication" class="mb-6" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Masukan email" autofocus required autocomplete="off">
                            </div>
                            <div class="mb-6 form-password-toggle">
                                <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" required autocomplete="off" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                                <div class="error-container" id="error-password"></div>
                            </div>
                            <div class="mb-8">
                                <div class="d-flex justify-content-between mt-8">
                                    <div class="form-check form-check-primary mb-0 ms-2">
                                        <input class="form-check-input" type="checkbox" id="remember-me" name="remember">
                                        <label class="form-check-label" for="remember-me">
                                            Ingat Saya
                                        </label>
                                    </div>
                                    <a href="{{ route('password.request') }}">
                                        <span>Lupa Password?</span>
                                    </a>
                                </div>
                            </div>
                            <div class="mb-6">
                                <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
                            </div>
                        </form>

                        <p class="text-center">
                            <span>Registrasi baru di platform kami?</span>
                            <a href="{{ route('register') }}">
                                <span>Buat akun baru</span>
                            </a>
                        </p>
                        <a href="{{ url('/') }}" class="btn btn-sm btn-secondary"><i class="icon-base bx bx-arrow-back"></i>&nbsp;Kembali Landing Page</a>
                    </div>
                </div>
            </div>
            <!-- /Register -->
        </div>
    </div>
@endsection

@section('page-script')
    @vite('resources/views/auth/js/login.js');
@endsection
