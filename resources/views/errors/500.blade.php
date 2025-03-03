@extends('layouts/blankLayout')

@section('title', 'Internal Server Error'.' • '.config('variables.templateName'))

@section('page-style')
    <!-- Page -->
    @vite(['resources/assets/vendor/scss/pages/page-misc.scss'])
@endsection


@section('content')
    <!-- Error -->
    <div class="container-xxl container-p-y">
        <div class="misc-wrapper">
            <h1 class="mb-2 mx-2" style="line-height: 6rem;font-size: 6rem;">500</h1>
            <h4 class="mb-2 mx-2">Internal Server Error ⚠️</h4>
            <p class="mb-6 mx-2">Ada kesalahan disisi aplikasi kami</p>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary">Kembali ke Dashboard</a>
            @else
                <a href="{{url('/')}}" class="btn btn-primary">Kembali ke landing page</a>
            @endif
            <div class="mt-6">
                <img src="{{asset('assets/img/illustrations/man-with-laptop-light.png')}}" alt="page-misc-error-light" width="500" class="img-fluid">
            </div>
        </div>
    </div>
    <!-- /Error -->
@endsection
