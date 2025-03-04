@extends('layouts/blankLayout')

@section('title', 'Verifikasi Email'.' • '.config('variables.templateName'))

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
                        <div class="alert alert-primary alert-dismissible" role="alert">
                            <h4 class="alert-heading d-flex align-items-center gap-1"><span class="alert-icon rounded"><i class="icon-base bx bx-check-shield"></i></span>Verifikasi Email!</h4>
                            <hr>
                            <p class="mb-0">Terima kasih <b>{{ \Illuminate\Support\Facades\Auth::user()->name }}</b> telah mendaftar layanan {{config('variables.templateName')}}. Sebelum memulai, verifikasi alamat email anda <b>dengan klik pada link</b> yang sudah kami kirim ke email. jika belum menerima, <b>klik tombol dibawah</b> untuk mengirimkan link kembali.</p>
                        </div>
                        <!-- /Logo -->
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
                        @if (session('status') == 'verification-link-sent')
                            <div class="alert alert-success alert-dismissible" role="alert">
                                Link verifikasi baru saja di kirim ke alamat email yang sudah kamu input saat registrasi. buka email anda dan klik link yang sudah kami kirimkan!
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="mt-4">
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary d-grid w-100">
                                    Kirim Ulang Verifikasi Email
                                </button>
                            </form>
                        </div>
                        <div class="mt-4 d-flex justify-content-center align-content-between">
                            <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-info">
                                Update Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
