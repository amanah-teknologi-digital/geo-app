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
                <ol class="breadcrumb flex">
                    <li class="breadcrumb-item">
                        <a href="#">Pengajuan</a>
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
                <div class="card-body pt-4">
                    <div class="table-responsive">
                        <table id="datatable" class="table">
                            <thead>
                            <tr>
                                <th style="border-top-width: 1px" nowrap class="text-center">No</th>
                                <th style="border-top-width: 1px" nowrap>Nama Ruangan</th>
                                <th style="border-top-width: 1px" nowrap>Tgl Booking</th>
                                <th style="border-top-width: 1px">Data Pengaju</th>
                                <th style="border-top-width: 1px">Kegiatan</th>
                                <th style="border-top-width: 1px" nowrap>Status Pengajuan</th>
                                <th style="border-top-width: 1px" nowrap class="text-center">Aksi</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <ul class="fa-ul ml-auto float-end mt-5">
                        <li>
                            <small><em>Pengajuan yang sudah diajukan tidak dapat dibatalkan.</em></small>
                        </li>
                    </ul>
                </div>
                <!-- /Account -->
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-hapus" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <form action="{{ route('pengajuanruangan.hapus') }}" method="POST">
                @csrf
                <input type="hidden" name="id_pengajuan" id="id_hapus">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel2">Hapus Pengajuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah yakin menghapus pengajuan ini?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('page-script')
    <script>
        const title = "{{ $title }}";
        const isTambah = "{{ $isTambah }}";
        const routeName = "{{ route('pengajuanruangan.getdata') }}"; // Ensure route name is valid
        const routeTambah = "{{ route('pengajuanruangan.tambah') }}"
    </script>
    @vite('resources/views/script_view/pengajuan_ruangan/list_pengajuan.js')
@endsection
