@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts/contentNavbarLayout')

@section('title', $title.' • '.config('variables.templateName'))

@section('page-style')
    @vite([
        'resources/assets/vendor/scss/pages/page-auth.scss',
        'resources/assets/css/custom.scss'
    ])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="#">Pengajuan</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('pengajuanruangan') }}">{{ (!empty(config('variables.namaLayananSewaRuangan')) ? config('variables.namaLayananSewaRuangan') : '') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </nav>
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
            @if (session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card mb-6">
                <div class="card-header d-flex justify-content-between align-items-center pb-4 border-bottom">
                    <h5 class="card-title mb-0"><i class="bx bx-plus mb-1"></i>&nbsp;Tambah Pengajuan</h5>
                    <a href="{{ route('pengajuanruangan') }}" class="btn btn-sm btn-secondary btn-sm">
                        <i class="bx bx-arrow-back"></i>&nbsp;Kembali
                    </a>
                </div>
                <div class="card-body pt-4">
                    <form id="formPengajuan" method="POST" action="{{ route('pengajuanruangan.dotambah') }}">
                        @csrf

                    </form>
                    <ul class="fa-ul ml-auto float-end mt-5">
                        <li>
                            <small><em>Jadwal yang tersedia adalah <b>H + 1</b> dari waktu pengajuan.</em></small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
{{--    <div class="modal modal-transparent fade" id="modals-transparent" tabindex="-1" style="border: none;">--}}
{{--        <div class="modal-dialog modal-lg">--}}
{{--            <div class="modal-content" style="background: rgba(0, 0, 0, 0);border: none;color: white;">--}}
{{--                <div class="modal-body">--}}
{{--                    <img id="kartu_idmodal" src="{{ $imageUrl }}" class="img-fluid w-100 h-100 object-fit-cover" alt="kartu ID">--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
@endsection
@section('page-script')
    <script>
        let routeGetJenisSurat = "{{ route('pengajuansurat.getjenissurat') }}";
    </script>
    @vite('resources/views/script_view/pengajuan_ruangan/tambah_pengajuan.js')
@endsection
