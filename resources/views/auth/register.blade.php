@extends('layouts/blankLayout')

@section('title', 'Registrasi'.' • '.config('variables.templateName'))

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
                <!-- Register Card -->
                <div class="card px-sm-6 px-0">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center mb-6">
                            <a href="{{url('/')}}" class="logo d-flex align-items-center me-auto me-xl-0 order-first">
                                <img src="{{ asset('landing_page_rss/teknikgeo.png') }}" alt="">
                                <h1 class="sitename">GeoReserve</h1>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <p class="mb-6">Nikmati layanan {{config('variables.templateName')}} setelah melakukan registrasi berikut !</p>
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    {{ $error }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endforeach
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <form id="formAuthentication" class="mb-6" action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-6">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Nama Lengkap" autofocus required autocomplete="off">
                            </div>
                            <div class="mb-6">
                                <label for="no_kartuid" class="form-label">Nomor Kartu ID (NRP/KTP) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="no_kartuid" name="no_kartuid" placeholder="Nomor Kartu ID (NRP/KTP)" required autocomplete="off">
                            </div>
                            <div class="mb-6">
                                <label for="email" class="form-label">Email (Non ITS)<span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required autocomplete="off">
                            </div>
                            <div class="mb-6">
                                <label for="no_telepon" class="form-label">No. Telepon <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="no_telepon" name="no_telepon" placeholder="contoh: 085924315876" required autocomplete="off">
                            </div>
                            <div class="mb-6">
                                <label for="file_kartuid" class="form-label">Unggah Kartu ID (KTM/KTP) <span class="text-danger">*</span> <span class="text-muted"><i><b>(File gambar max 5 mb)</b></i></span></label>
                                <input type="file" class="form-control" id="file_kartuid" name="file_kartuid" accept="image/*" required>
                            </div>
                            <div class="mb-6 form-password-toggle">
                                <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                                <div class="error-container" id="error-password"></div>
                            </div>
                            <div class="mb-6 form-password-toggle">
                                <label class="form-label" for="password_confirmation">Konfirmasi Password <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                                <div class="error-container" id="error-password_confirmation"></div>
                            </div>
                            <div class="my-8">
                                <div class="form-check form-check-primary mb-0 ms-2">
                                    <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms" required>
                                    <label class="form-check-label" for="terms-conditions">
                                        I agree to
                                        <a href="javascript:void(0);">privacy policy & terms <span class="text-danger">*</span></a>
                                    </label>
                                </div>
                                <div class="error-container" id="error-terms"></div>
                            </div>
                            <button class="btn btn-primary d-grid w-100">
                                Daftar
                            </button>
                        </form>

                        <p class="text-center">
                            <span>Sudah punya akun?</span>
                            <a href="{{ route('login') }}">
                                <span>Ke halaman Login</span>
                            </a>
                        </p>
                        <a href="{{ url('/') }}" class="btn btn-sm btn-secondary"><i class="icon-base bx bx-arrow-back"></i>&nbsp;Kembali Landing Page</a>
                    </div>
                </div>
                <!-- Register Card -->
            </div>
        </div>
    </div>
@endsection
@section('page-script')
    @vite('resources/views/auth/js/register.js')
@endsection
