@extends('layouts/blankLayout')

@section('title', 'Lupa Password'.' • '.config('variables.templateName'))

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

                <!-- Forgot Password -->
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
                        <h4 class="mb-1">Lupa Password? 🔒</h4>
                        <p class="mb-6">Masukan email anda dan kami akan mengirimkan link intruksi untuk mereset password anda.</p>
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
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                Link reset password sudah dikirim ke alamat email anda, cek dan buka link tersebut untuk mereset password anda.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <form id="formAuthentication" class="mb-6" action="{{ route('password.email') }}" method="POST">
                            @csrf
                            <div class="mb-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Masukan Email" autofocus>
                            </div>
                            <button class="btn btn-primary d-grid w-100">Kirim Reset Link</button>
                        </form>
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="d-flex justify-content-center">
                                <i class="bx bx-chevron-left scaleX-n1-rtl me-1"></i>
                                Kembali ke halaman Login
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /Forgot Password -->
            </div>
        </div>
    </div>
@endsection
@section('page-script')
    @vite('resources/views/auth/js/lupa_password.js')
@endsection
