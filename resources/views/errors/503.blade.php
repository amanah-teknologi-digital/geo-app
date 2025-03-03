@extends('layouts/blankLayout')

@section('title', 'Under Maintenance'.' • '.config('variables.templateName'))

@section('page-style')
    <!-- Page -->
    @vite(['resources/assets/vendor/scss/pages/page-misc.scss'])
@endsection

@section('content')
    <!--Under Maintenance -->
    <div class="container-xxl container-p-y">
        <div class="misc-wrapper">
            <h3 class="mb-2 mx-2">Aplikasi masih dalam perbaikan! 🚧</h3>
            <p class="mb-6 mx-2">
                Maaf atas ketidaknyamanan ini, kami sedang melakukan perbaikan pada saat ini
            </p>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary">Kembali ke Dashboard</a>
            @else
                <a href="{{url('/')}}" class="btn btn-primary">Kembali ke landing page</a>
            @endif
            <div class="mt-6">
                <img src="{{asset('assets/img/illustrations/girl-doing-yoga-light.png')}}" alt="girl-doing-yoga-light" width="500" class="img-fluid">
            </div>
        </div>
    </div>
    <!-- /Under Maintenance -->
@endsection
