@extends('layouts/blankLayout')

@section('title', 'Reset Password'.' • '.config('variables.templateName'))

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
                        <p class="mb-6">Masukan password baru dan konfirmasi passwordnya!</p>
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
                        <form id="formAuthentication" class="mb-6" action="{{ route('password.store') }}" method="POST">
                            @csrf
                            <!-- Password Reset Token -->
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">
                            <div class="mb-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $request->email) }}" placeholder="Email" autofocus required autocomplete="username">
                            </div>
                            <div class="mb-6 form-password-toggle">
                                <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required aria-describedby="password" autocomplete="new-password" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                                <div class="error-container" id="error-password"></div>
                            </div>
                            <div class="mb-6 form-password-toggle">
                                <label class="form-label" for="password_confirmation">Konfirmasi Password <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required aria-describedby="password" autocomplete="new-password" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                                <div class="error-container" id="error-password_confirmation"></div>
                            </div>
                            <button class="btn btn-primary d-grid w-100">
                                Reset Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-script')
    @vite('resources/views/auth/js/reset_password.js')
@endsection
